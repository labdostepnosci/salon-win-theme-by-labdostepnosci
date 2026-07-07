<?php
/**
 * GitHub Releases updater for the Salon Win theme.
 *
 * @package Salon_Win
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'add_filter' ) || ! function_exists( 'add_action' ) ) {
    return;
}

if ( ! class_exists( 'Salon_Win_GitHub_Theme_Updater' ) ) {
    final class Salon_Win_GitHub_Theme_Updater {
        private const THEME_SLUG       = 'salon-win';
        private const THEME_NAME       = 'Salon Win by labdostepnosci';
        private const REPOSITORY       = 'labdostepnosci/salon-win';
        private const REPOSITORY_URL   = 'https://github.com/labdostepnosci/salon-win';
        private const API_URL          = 'https://api.github.com/repos/labdostepnosci/salon-win/releases/latest';
        private const TRANSIENT_NAME   = 'salon_win_github_release';
        private const CACHE_TTL        = 6 * HOUR_IN_SECONDS;
        private const ERROR_CACHE_TTL  = 30 * MINUTE_IN_SECONDS;
        private const MAX_PACKAGE_SIZE = 50 * 1024 * 1024;
        private const PACKAGE_SCHEME   = 'salon-win-github-release://';

        public function __construct() {
            add_filter( 'pre_set_site_transient_update_themes', array( $this, 'check_for_update' ), 99 );
            add_filter( 'themes_api', array( $this, 'theme_information' ), 99, 3 );
            add_filter( 'upgrader_pre_download', array( $this, 'download_private_package' ), 99, 4 );
            add_action( 'upgrader_process_complete', array( $this, 'clear_cache_after_update' ), 10, 2 );
        }

        /**
         * Add the latest GitHub Release to WordPress theme updates.
         *
         * @param mixed $transient Theme update transient.
         * @return mixed
         */
        public function check_for_update( $transient ) {
            if ( ! is_object( $transient ) ) {
                return $transient;
            }

            $current_version = $this->current_version();
            $release         = $this->latest_release();

            if (
                '' === $current_version ||
                ! is_array( $release ) ||
                empty( $release['version'] ) ||
                empty( $release['package'] ) ||
                ! version_compare( $release['version'], $current_version, '>' )
            ) {
                return $transient;
            }

            if ( ! isset( $transient->response ) || ! is_array( $transient->response ) ) {
                $transient->response = array();
            }

            $transient->response[ self::THEME_SLUG ] = array(
                'theme'        => self::THEME_SLUG,
                'new_version'  => $release['version'],
                'url'          => self::REPOSITORY_URL,
                'package'      => $release['package'],
                'requires'     => '6.0',
                'requires_php' => '8.1',
            );

            return $transient;
        }

        /**
         * Supply the theme details shown in the WordPress update dialog.
         *
         * @param false|object|array $result Existing Themes API result.
         * @param string             $action Requested Themes API action.
         * @param object             $args   API arguments.
         * @return false|object|array
         */
        public function theme_information( $result, $action, $args ) {
            if (
                'theme_information' !== $action ||
                ! is_object( $args ) ||
                self::THEME_SLUG !== ( $args->slug ?? '' )
            ) {
                return $result;
            }

            $release = $this->latest_release();
            if ( ! is_array( $release ) || empty( $release['version'] ) ) {
                return $result;
            }

            $release_body = '' !== $release['body']
                ? nl2br( esc_html( $release['body'] ) )
                : esc_html__( 'Szczegóły zmian znajdują się na stronie wydania w GitHubie.', 'salon-win' );

            return (object) array(
                'name'          => self::THEME_NAME,
                'slug'          => self::THEME_SLUG,
                'version'       => $release['version'],
                'author'        => 'labdostepnosci',
                'homepage'      => self::REPOSITORY_URL,
                'requires'      => '6.0',
                'requires_php'  => '8.1',
                'last_updated'  => $release['published_at'],
                'download_link' => $release['package'],
                'sections'      => array(
                    'description' => esc_html__( 'Motyw WordPress dla strony Salon Win.', 'salon-win' ),
                    'changelog'   => $release_body,
                ),
            );
        }

        /**
         * Download an authenticated release asset for a private repository.
         *
         * GitHub's browser_download_url is sufficient for public assets. Private
         * assets are requested through their API URL with Authorization and
         * application/octet-stream. GitHub may return either the file or a 302
         * redirect; the redirected request is deliberately sent without the token.
         *
         * @param false|string|WP_Error $reply      Existing pre-download result.
         * @param string                $package    Package URL.
         * @param WP_Upgrader           $upgrader   Upgrader instance.
         * @param array                 $hook_extra Upgrade context.
         * @return false|string|WP_Error
         */
        public function download_private_package( $reply, $package, $upgrader, $hook_extra ) {
            unset( $upgrader, $hook_extra );

            if ( ! is_string( $package ) || ! str_starts_with( $package, self::PACKAGE_SCHEME ) ) {
                return $reply;
            }

            $token = $this->github_token();
            $url   = rawurldecode( substr( $package, strlen( self::PACKAGE_SCHEME ) ) );

            if ( '' === $token || ! $this->is_allowed_api_url( $url ) ) {
                return new WP_Error(
                    'salon_win_updater_invalid_package',
                    esc_html__( 'Nie można bezpiecznie pobrać paczki aktualizacji z GitHuba.', 'salon-win' )
                );
            }

            $response = wp_remote_get(
                $url,
                array(
                    'timeout'             => 30,
                    'redirection'         => 0,
                    'limit_response_size' => self::MAX_PACKAGE_SIZE,
                    'headers'             => $this->request_headers( 'application/octet-stream' ),
                )
            );

            if ( is_wp_error( $response ) ) {
                return $response;
            }

            $status = (int) wp_remote_retrieve_response_code( $response );

            if ( 200 === $status ) {
                return $this->save_response_body( $response );
            }

            if ( $status >= 300 && $status < 400 ) {
                $location = (string) wp_remote_retrieve_header( $response, 'location' );

                if ( ! $this->is_allowed_download_url( $location ) ) {
                    return new WP_Error(
                        'salon_win_updater_invalid_redirect',
                        esc_html__( 'GitHub zwrócił nieprawidłowy adres pobierania aktualizacji.', 'salon-win' )
                    );
                }

                return $this->download_redirected_package( $location );
            }

            return new WP_Error(
                'salon_win_updater_download_failed',
                sprintf(
                    /* translators: %d: HTTP response status. */
                    esc_html__( 'Nie udało się pobrać aktualizacji z GitHuba. Kod HTTP: %d.', 'salon-win' ),
                    $status
                )
            );
        }

        /**
         * Clear cached release metadata after updating this theme.
         *
         * @param WP_Upgrader $upgrader   Upgrader instance.
         * @param array       $hook_extra Upgrade context.
         * @return void
         */
        public function clear_cache_after_update( $upgrader, $hook_extra ) {
            unset( $upgrader );

            if (
                ! is_array( $hook_extra ) ||
                'theme' !== ( $hook_extra['type'] ?? '' ) ||
                'update' !== ( $hook_extra['action'] ?? '' )
            ) {
                return;
            }

            $themes = isset( $hook_extra['themes'] ) && is_array( $hook_extra['themes'] )
                ? $hook_extra['themes']
                : array( $hook_extra['theme'] ?? '' );

            if ( in_array( self::THEME_SLUG, $themes, true ) ) {
                delete_site_transient( self::TRANSIENT_NAME );
            }
        }

        /**
         * Return the version declared by the installed theme's style.css.
         *
         * @return string
         */
        private function current_version() {
            if ( ! function_exists( 'wp_get_theme' ) ) {
                return '';
            }

            $theme = wp_get_theme( self::THEME_SLUG );
            if ( ! $theme->exists() ) {
                return '';
            }

            return sanitize_text_field( (string) $theme->get( 'Version' ) );
        }

        /**
         * Fetch and cache sanitized metadata for the latest release.
         *
         * @return array|null
         */
        private function latest_release() {
            $cached = get_site_transient( self::TRANSIENT_NAME );

            if ( is_array( $cached ) && isset( $cached['status'] ) ) {
                return 'ok' === $cached['status'] && isset( $cached['release'] ) && is_array( $cached['release'] )
                    ? $cached['release']
                    : null;
            }

            $response = wp_remote_get(
                self::API_URL,
                array(
                    'timeout' => 10,
                    'headers' => $this->request_headers( 'application/vnd.github+json' ),
                )
            );

            if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
                $this->cache_error();
                return null;
            }

            $data = json_decode( wp_remote_retrieve_body( $response ), true );

            if ( ! is_array( $data ) || JSON_ERROR_NONE !== json_last_error() || empty( $data['tag_name'] ) ) {
                $this->cache_error();
                return null;
            }

            $version = $this->normalize_version( $data['tag_name'] );
            if ( '' === $version ) {
                $this->cache_error();
                return null;
            }

            $package = $this->find_package( $data, $version );
            $release = array(
                'version'      => $version,
                'tag_name'     => sanitize_text_field( (string) $data['tag_name'] ),
                'name'         => sanitize_text_field( (string) ( $data['name'] ?? '' ) ),
                'body'         => sanitize_textarea_field( (string) ( $data['body'] ?? '' ) ),
                'published_at' => sanitize_text_field( (string) ( $data['published_at'] ?? '' ) ),
                'html_url'     => esc_url_raw( (string) ( $data['html_url'] ?? self::REPOSITORY_URL ) ),
                'package'      => $package,
            );

            set_site_transient(
                self::TRANSIENT_NAME,
                array(
                    'status'  => 'ok',
                    'release' => $release,
                ),
                self::CACHE_TTL
            );

            return $release;
        }

        /**
         * Find the versioned release asset, with zipball_url as a fallback.
         *
         * @param array  $data    GitHub release data.
         * @param string $version Normalized release version.
         * @return string
         */
        private function find_package( $data, $version ) {
            $expected_name = 'salon-win-theme-' . $version . '.zip';

            if ( isset( $data['assets'] ) && is_array( $data['assets'] ) ) {
                foreach ( $data['assets'] as $asset ) {
                    if ( ! is_array( $asset ) || $expected_name !== ( $asset['name'] ?? '' ) ) {
                        continue;
                    }

                    $browser_url = esc_url_raw( (string) ( $asset['browser_download_url'] ?? '' ) );
                    $api_url     = esc_url_raw( (string) ( $asset['url'] ?? '' ) );

                    if ( '' !== $this->github_token() && $this->is_allowed_api_url( $api_url ) ) {
                        return self::PACKAGE_SCHEME . rawurlencode( $api_url );
                    }

                    if ( $this->is_allowed_download_url( $browser_url ) ) {
                        return $browser_url;
                    }
                }
            }

            $zipball_url = esc_url_raw( (string) ( $data['zipball_url'] ?? '' ) );
            if ( ! $this->is_allowed_api_url( $zipball_url ) ) {
                return '';
            }

            if ( '' !== $this->github_token() ) {
                return self::PACKAGE_SCHEME . rawurlencode( $zipball_url );
            }

            return $zipball_url;
        }

        /**
         * Normalize and validate a v-prefixed SemVer release tag.
         *
         * @param mixed $tag Release tag.
         * @return string
         */
        private function normalize_version( $tag ) {
            $tag = sanitize_text_field( (string) $tag );

            if ( ! preg_match( '/^v?(\d+\.\d+\.\d+(?:-[0-9A-Za-z.-]+)?(?:\+[0-9A-Za-z.-]+)?)$/', $tag, $matches ) ) {
                return '';
            }

            return $matches[1];
        }

        /**
         * Build headers for GitHub API requests.
         *
         * @param string $accept Accept header value.
         * @return array
         */
        private function request_headers( $accept ) {
            $headers = array(
                'Accept'               => $accept,
                'User-Agent'           => 'Salon-Win-Theme-Updater',
                'X-GitHub-Api-Version' => '2022-11-28',
            );

            $token = $this->github_token();
            if ( '' !== $token ) {
                $headers['Authorization'] = 'Bearer ' . $token;
            }

            return $headers;
        }

        /**
         * Read the optional private-repository token from wp-config.php.
         *
         * @return string
         */
        private function github_token() {
            if ( ! defined( 'SALON_WIN_GITHUB_TOKEN' ) ) {
                return '';
            }

            $token = constant( 'SALON_WIN_GITHUB_TOKEN' );
            return is_string( $token ) ? trim( $token ) : '';
        }

        /**
         * Store a failed lookup briefly so frontend requests do not retry it.
         *
         * @return void
         */
        private function cache_error() {
            set_site_transient(
                self::TRANSIENT_NAME,
                array( 'status' => 'error' ),
                self::ERROR_CACHE_TTL
            );
        }

        /**
         * Download a trusted GitHub redirect without forwarding the token.
         *
         * @param string $url Signed GitHub download URL.
         * @return string|WP_Error
         */
        private function download_redirected_package( $url ) {
            $temporary_file = wp_tempnam( 'salon-win-theme-update.zip' );

            if ( ! $temporary_file ) {
                return new WP_Error(
                    'salon_win_updater_temp_file_failed',
                    esc_html__( 'Nie udało się utworzyć pliku tymczasowego aktualizacji.', 'salon-win' )
                );
            }

            $response = wp_remote_get(
                $url,
                array(
                    'timeout'             => 300,
                    'redirection'         => 3,
                    'stream'              => true,
                    'filename'            => $temporary_file,
                    'limit_response_size' => self::MAX_PACKAGE_SIZE,
                    'headers'             => array(
                        'User-Agent' => 'Salon-Win-Theme-Updater',
                    ),
                )
            );

            if ( is_wp_error( $response ) ) {
                wp_delete_file( $temporary_file );
                return $response;
            }

            $status = (int) wp_remote_retrieve_response_code( $response );
            $size   = file_exists( $temporary_file ) ? filesize( $temporary_file ) : 0;

            if ( $status < 200 || $status >= 300 || ! $size || $size > self::MAX_PACKAGE_SIZE ) {
                wp_delete_file( $temporary_file );
                return new WP_Error(
                    'salon_win_updater_invalid_download',
                    esc_html__( 'GitHub nie zwrócił prawidłowej paczki aktualizacji.', 'salon-win' )
                );
            }

            return $temporary_file;
        }

        /**
         * Save a package returned directly by the GitHub API.
         *
         * @param array $response WordPress HTTP response.
         * @return string|WP_Error
         */
        private function save_response_body( $response ) {
            $body = wp_remote_retrieve_body( $response );

            if ( '' === $body || strlen( $body ) > self::MAX_PACKAGE_SIZE ) {
                return new WP_Error(
                    'salon_win_updater_empty_package',
                    esc_html__( 'GitHub zwrócił pustą albo zbyt dużą paczkę aktualizacji.', 'salon-win' )
                );
            }

            $temporary_file = wp_tempnam( 'salon-win-theme-update.zip' );
            if ( ! $temporary_file ) {
                return new WP_Error(
                    'salon_win_updater_temp_file_failed',
                    esc_html__( 'Nie udało się utworzyć pliku tymczasowego aktualizacji.', 'salon-win' )
                );
            }

            $written = file_put_contents( $temporary_file, $body );
            if ( false === $written || strlen( $body ) !== $written ) {
                wp_delete_file( $temporary_file );
                return new WP_Error(
                    'salon_win_updater_write_failed',
                    esc_html__( 'Nie udało się zapisać paczki aktualizacji.', 'salon-win' )
                );
            }

            return $temporary_file;
        }

        /**
         * Confirm that a package API URL belongs to this repository.
         *
         * @param string $url URL to check.
         * @return bool
         */
        private function is_allowed_api_url( $url ) {
            $parts = wp_parse_url( $url );

            return is_array( $parts )
                && 'https' === strtolower( (string) ( $parts['scheme'] ?? '' ) )
                && 'api.github.com' === strtolower( (string) ( $parts['host'] ?? '' ) )
                && str_starts_with( (string) ( $parts['path'] ?? '' ), '/repos/' . self::REPOSITORY . '/' );
        }

        /**
         * Allow only HTTPS download hosts controlled by GitHub.
         *
         * @param string $url URL to check.
         * @return bool
         */
        private function is_allowed_download_url( $url ) {
            $parts = wp_parse_url( $url );
            if ( ! is_array( $parts ) || 'https' !== strtolower( (string) ( $parts['scheme'] ?? '' ) ) ) {
                return false;
            }

            $host = strtolower( (string) ( $parts['host'] ?? '' ) );

            return 'github.com' === $host
                || str_ends_with( $host, '.github.com' )
                || 'githubusercontent.com' === $host
                || str_ends_with( $host, '.githubusercontent.com' );
        }
    }
}

/**
 * Register the updater after WordPress has initialized the active theme.
 *
 * @return void
 */
function salon_win_register_github_theme_updater() {
    new Salon_Win_GitHub_Theme_Updater();
}

add_action( 'after_setup_theme', 'salon_win_register_github_theme_updater', 20 );

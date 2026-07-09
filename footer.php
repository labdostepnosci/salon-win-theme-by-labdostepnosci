from __future__ import annotations

import argparse
import csv
import json
import re
from dataclasses import dataclass
from datetime import datetime, timezone
from pathlib import Path
from typing import Iterable
from urllib.parse import urlparse
from xml.sax.saxutils import escape
import xml.etree.ElementTree as ET


DEFAULT_SINCE = "2026-01-01"
DEFAULT_SITE_URL = "https://salon-win.pl"


@dataclass
class FbPost:
    date: datetime
    title: str
    message: str
    permalink: str
    image_url: str
    categories: list[str]
    tags: list[str]
    status: str = "draft"


def cdata(value: str) -> str:
    return "<![CDATA[" + str(value or "").replace("]]>", "]]]]><![CDATA[>") + "]]>"


def slugify_pl(value: str) -> str:
    repl = str.maketrans(
        {
            "ą": "a",
            "ć": "c",
            "ę": "e",
            "ł": "l",
            "ń": "n",
            "ó": "o",
            "ś": "s",
            "ż": "z",
            "ź": "z",
            "Ą": "a",
            "Ć": "c",
            "Ę": "e",
            "Ł": "l",
            "Ń": "n",
            "Ó": "o",
            "Ś": "s",
            "Ż": "z",
            "Ź": "z",
        }
    )
    value = value.translate(repl).lower()
    value = re.sub(r"[^a-z0-9]+", "-", value).strip("-")
    return value[:80].strip("-") or "aktualnosc"


def parse_date(value: str) -> datetime:
    value = (value or "").strip()
    if not value:
        return datetime.now(timezone.utc)
    value = value.replace("Z", "+00:00")
    try:
        parsed = datetime.fromisoformat(value)
    except ValueError:
        parsed = datetime.strptime(value[:10], "%Y-%m-%d")
    if parsed.tzinfo is None:
        parsed = parsed.replace(tzinfo=timezone.utc)
    return parsed.astimezone(timezone.utc)


def split_terms(value: str, fallback: Iterable[str]) -> list[str]:
    items = [item.strip() for item in re.split(r"[;,]", value or "") if item.strip()]
    return items or list(fallback)


def title_from_message(message: str, date: datetime) -> str:
    text = " ".join((message or "").split())
    text = re.sub(r"https?://\S+", "", text).strip()
    if not text:
        return "Aktualność z Facebooka - " + date.strftime("%Y-%m-%d")
    words = text.split()
    title = " ".join(words[:10])
    if len(words) > 10:
        title += "..."
    return title


def read_csv(path: Path) -> list[FbPost]:
    posts: list[FbPost] = []
    with path.open("r", encoding="utf-8-sig", newline="") as handle:
        reader = csv.DictReader(handle)
        for row in reader:
            date = parse_date(row.get("date", ""))
            message = (row.get("message") or "").strip()
            title = (row.get("title") or "").strip() or title_from_message(message, date)
            posts.append(
                FbPost(
                    date=date,
                    title=title,
                    message=message,
                    permalink=(row.get("permalink") or "").strip(),
                    image_url=(row.get("image_url") or "").strip(),
                    categories=split_terms(row.get("categories", ""), ["Aktualności", "Facebook"]),
                    tags=split_terms(row.get("tags", ""), ["Salon Win"]),
                    status=(row.get("status") or "draft").strip() or "draft",
                )
            )
    return posts


def extract_image_from_graph_post(post: dict) -> str:
    if post.get("full_picture"):
        return post["full_picture"]
    attachments = post.get("attachments", {})
    data = attachments.get("data", []) if isinstance(attachments, dict) else []
    for item in data:
        media = item.get("media", {}) if isinstance(item, dict) else {}
        image = media.get("image", {}) if isinstance(media, dict) else {}
        if image.get("src"):
            return image["src"]
    return ""


def read_graph_json(path: Path) -> list[FbPost]:
    raw = json.loads(path.read_text(encoding="utf-8"))
    data = raw.get("data", raw if isinstance(raw, list) else [])
    posts: list[FbPost] = []
    for item in data:
        if not isinstance(item, dict):
            continue
        message = (item.get("message") or item.get("story") or "").strip()
        if not message:
            continue
        date = parse_date(item.get("created_time", ""))
        title = title_from_message(message, date)
        posts.append(
            FbPost(
                date=date,
                title=title,
                message=message,
                permalink=(item.get("permalink_url") or "").strip(),
                image_url=extract_image_from_graph_post(item),
                categories=["Aktualności", "Facebook"],
                tags=["Salon Win", "Jasło"],
                status="draft",
            )
        )
    return posts


def content_html(post: FbPost) -> str:
    paragraphs = [p.strip() for p in re.split(r"\n\s*\n", post.message) if p.strip()]
    parts = []
    if post.image_url:
        parts.append(
            '<!-- wp:paragraph -->\n<p><em>Obrazek z posta Facebook można ustawić jako obrazek wyróżniający po imporcie.</em></p>\n<!-- /wp:paragraph -->'
        )
    for paragraph in paragraphs:
        parts.append("<!-- wp:paragraph -->\n<p>" + escape(paragraph) + "</p>\n<!-- /wp:paragraph -->")
    if post.permalink:
        parts.append(
            '<!-- wp:paragraph -->\n<p><a href="'
            + escape(post.permalink)
            + '" target="_blank" rel="noopener">Zobacz oryginalny post na Facebooku</a></p>\n<!-- /wp:paragraph -->'
        )
    return "\n\n".join(parts)


def build_wxr(posts: list[FbPost], site_url: str, since: datetime) -> str:
    filtered = [post for post in posts if post.date >= since]
    filtered.sort(key=lambda post: post.date)
    categories = []
    tags = []
    for post in filtered:
        for category in post.categories:
            if category not in categories:
                categories.append(category)
        for tag in post.tags:
            if tag not in tags:
                tags.append(tag)

    lines = [
        '<?xml version="1.0" encoding="UTF-8" ?>',
        '<rss version="2.0" xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:wp="http://wordpress.org/export/1.2/">',
        "<channel>",
        "<title>Salon Win - import aktualności z Facebooka</title>",
        f"<link>{site_url}</link>",
        "<description>Aktualności z fanpage Facebook od 1 stycznia</description>",
        "<language>pl-PL</language>",
        "<wp:wxr_version>1.2</wp:wxr_version>",
        f"<wp:base_site_url>{site_url}</wp:base_site_url>",
        f"<wp:base_blog_url>{site_url}</wp:base_blog_url>",
        "<wp:author><wp:author_id>1</wp:author_id><wp:author_login><![CDATA[admin]]></wp:author_login><wp:author_display_name><![CDATA[Salon Win]]></wp:author_display_name></wp:author>",
    ]

    for index, category in enumerate(categories, 1):
        lines.extend(
            [
                "<wp:category>",
                f"<wp:term_id>{index}</wp:term_id>",
                f"<wp:category_nicename>{cdata(slugify_pl(category))}</wp:category_nicename>",
                "<wp:category_parent><![CDATA[]]></wp:category_parent>",
                f"<wp:cat_name>{cdata(category)}</wp:cat_name>",
                "</wp:category>",
            ]
        )

    for index, tag in enumerate(tags, 1):
        lines.extend(
            [
                "<wp:tag>",
                f"<wp:term_id>{500 + index}</wp:term_id>",
                f"<wp:tag_slug>{cdata(slugify_pl(tag))}</wp:tag_slug>",
                f"<wp:tag_name>{cdata(tag)}</wp:tag_name>",
                "</wp:tag>",
            ]
        )

    used_slugs: set[str] = set()
    for index, post in enumerate(filtered, 1):
        base_slug = slugify_pl(post.title)
        slug = base_slug
        counter = 2
        while slug in used_slugs:
            slug = f"{base_slug}-{counter}"
            counter += 1
        used_slugs.add(slug)

        post_id = 3000 + index
        post_date = post.date.strftime("%Y-%m-%d %H:%M:%S")
        pub_date = post.date.strftime("%a, %d %b %Y %H:%M:%S +0000")
        excerpt = " ".join(post.message.split())[:240]

        lines.extend(
            [
                "<item>",
                f"<title>{cdata(post.title)}</title>",
                f"<link>{site_url}/{slug}/</link>",
                f"<pubDate>{pub_date}</pubDate>",
                "<dc:creator><![CDATA[admin]]></dc:creator>",
            ]
        )
        for category in post.categories:
            lines.append(
                f'<category domain="category" nicename="{slugify_pl(category)}">{cdata(category)}</category>'
            )
        for tag in post.tags:
            lines.append(f'<category domain="post_tag" nicename="{slugify_pl(tag)}">{cdata(tag)}</category>')

        lines.extend(
            [
                f'<guid isPermaLink="false">{cdata(site_url + "/?p=" + str(post_id))}</guid>',
                "<description></description>",
                f"<content:encoded>{cdata(content_html(post))}</content:encoded>",
                f"<excerpt:encoded>{cdata(excerpt)}</excerpt:encoded>",
                f"<wp:post_id>{post_id}</wp:post_id>",
                f"<wp:post_date>{cdata(post_date)}</wp:post_date>",
                f"<wp:post_date_gmt>{cdata(post_date)}</wp:post_date_gmt>",
                "<wp:comment_status><![CDATA[closed]]></wp:comment_status>",
                "<wp:ping_status><![CDATA[closed]]></wp:ping_status>",
                f"<wp:post_name>{cdata(slug)}</wp:post_name>",
                f"<wp:status>{cdata(post.status if post.status in {'draft', 'publish', 'pending'} else 'draft')}</wp:status>",
                "<wp:post_parent>0</wp:post_parent>",
                "<wp:menu_order>0</wp:menu_order>",
                "<wp:post_type><![CDATA[post]]></wp:post_type>",
                "<wp:post_password><![CDATA[]]></wp:post_password>",
                "<wp:is_sticky>0</wp:is_sticky>",
            ]
        )

        for key, value in [
            ("_facebook_permalink", post.permalink),
            ("_facebook_image_url", post.image_url),
            ("_yoast_wpseo_metadesc", excerpt),
            ("rank_math_description", excerpt),
        ]:
            lines.extend(
                [
                    "<wp:postmeta>",
                    f"<wp:meta_key>{cdata(key)}</wp:meta_key>",
                    f"<wp:meta_value>{cdata(value)}</wp:meta_value>",
                    "</wp:postmeta>",
                ]
            )
        lines.append("</item>")

    lines.extend(["</channel>", "</rss>"])
    return "\n".join(lines)


def load_posts(path: Path) -> list[FbPost]:
    suffix = path.suffix.lower()
    if suffix == ".csv":
        return read_csv(path)
    if suffix == ".json":
        return read_graph_json(path)
    raise SystemExit("Obsługiwane formaty wejściowe: .csv albo .json")


def main() -> None:
    parser = argparse.ArgumentParser(description="Konwersja postów Facebook do importu WordPress WXR/XML.")
    parser.add_argument("--input", required=True, help="Plik CSV albo JSON z postami Facebook.")
    parser.add_argument("--output", default="facebook-posty-wordpress-import.xml", help="Docelowy plik XML.")
    parser.add_argument("--since", default=DEFAULT_SINCE, help="Data od której importować, np. 2026-01-01.")
    parser.add_argument("--site-url", default=DEFAULT_SITE_URL, help="Adres strony WordPress.")
    args = parser.parse_args()

    input_path = Path(args.input)
    output_path = Path(args.output)
    since = parse_date(args.since)
    posts = load_posts(input_path)
    wxr = build_wxr(posts, args.site_url.rstrip("/"), since)
    output_path.write_text(wxr, encoding="utf-8")
    ET.parse(output_path)
    print(f"Zapisano: {output_path.resolve()}")
    print(f"Źródłowych postów: {len(posts)}")
    print(f"Import od: {since.date().isoformat()}")


if __name__ == "__main__":
    main()

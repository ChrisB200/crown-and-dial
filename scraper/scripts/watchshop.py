import logging
from typing import List

from playwright.async_api import Browser, Page
from scripts.config import load_env

logger = logging.getLogger(__name__)


def format_price(text: str) -> float:
    text = text.strip()

    if not text:
        return 0.0

    text = text.replace("£", "").replace(".-", "").replace(",", "").strip()

    return float(text)


class WatchShop:
    BASE_URL = "https://www.watch.co.uk"
    URLS = {
        "luxury": "luxury-watches",
        "smart": "smartwatch",
        "sports": "sports-watches",
        "casual": "swiss-watches",
        "classic": "running-watches",
    }

    def __init__(self, browser: Browser, page: Page):
        self.browser = browser
        self.page = page
        self.watches = []
        self.max = int(load_env("NUM_WATCHES", 1))

    @classmethod
    async def create(cls, browser: Browser):
        page = await browser.new_page()
        await page.goto(cls.BASE_URL)
        self = cls(browser, page)
        return self

    async def run(self):
        await self.page.get_by_role("button", name="Accept all").click()
        logger.info("Accepted cookies")
        for key, value in self.URLS.items():
            urls = await self.get_watch_urls(value)
            await self.get_watch_listings(urls, key)

    async def get_watch_urls(self, url: str):
        url = f"{self.BASE_URL}/{url}"
        await self.page.goto(url)
        logger.info("Travelled to %s", url)

        watch_elements = self.page.locator(".p-2.w-1\\/2.md\\:w-1\\/3.lg\\:w-1\\/4")
        watch_elements = await watch_elements.all()

        watch_urls = []
        for count, div in enumerate(watch_elements):
            anchor = div.locator("a").first
            href = await anchor.get_attribute("href")
            watch_urls.append(href)
            logger.debug("Found %s", href)

            if count >= self.max:
                break

        logger.info("Found %s watches on %s", len(watch_urls), url)

        return watch_urls

    async def get_watch_listings(self, urls: List[str], category: str):
        for url in urls:
            try:
                watch = await self.get_watch_listing(url, category)
                self.watches.append(watch)
            except Exception:
                pass

    async def get_watch_listing(self, url: str, category: str):
        url = f"{self.BASE_URL}/{url}"
        await self.page.goto(url)
        logger.info("Travelled to %s", url)

        listing = self.page.locator("#pdpContainer").first

        title = listing.locator("h1").first
        name = await title.inner_text()
        logger.debug("Found name %s", name)

        strong = listing.locator("strong").filter(has_text="£").first
        price_raw = str(await strong.text_content())
        price = format_price(price_raw)
        logger.debug("Found price %s", price)

        breadcrumb_items = self.page.locator("li.breadcrumb-item")
        breadcrumb_brand = breadcrumb_items.nth(1).locator("a").first
        brand = await breadcrumb_brand.inner_text()
        logger.debug("Found brand %s", brand)

        await listing.get_by_role("button", name="Read more").click()
        description_container = listing.locator(".pdp__description").first
        description = await description_container.inner_text()
        description = str(description).strip()
        logger.debug("Found description %s", description)

        img_urls = []
        tns_items = await listing.locator(".tns-item").all()
        for item in tns_items:
            img = item.locator("img").first
            href = await img.get_attribute("src")
            img_urls.append(href)

        logger.debug("Found %s images", len(img_urls))

        watch = {
            "name": name,
            "price": price,
            "brand": brand,
            "description": description,
            "img_urls": img_urls,
            "category": category,
        }

        logger.info("Scraped watch %s", name)

        return watch

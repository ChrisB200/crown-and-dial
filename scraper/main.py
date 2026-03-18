import asyncio
import logging

from playwright.async_api import async_playwright
from scripts.repository import RepositoryService
from scripts.watchshop import WatchShop

logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s | %(levelname)s | %(name)s | %(message)s",
)


logger = logging.getLogger(__name__)


async def run(headless=True):
    async with async_playwright() as p:
        logger.info("Started seeding watches")
        browser = await p.firefox.launch(headless=headless)
        logger.debug("Opened firefox")

        watchshop = await WatchShop.create(browser)
        logger.debug("Instantiated WatchShop class")
        await watchshop.run()

        repository = RepositoryService()
        repository.commit(watchshop.watches)


asyncio.run(run())

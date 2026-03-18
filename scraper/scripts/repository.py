import logging

import pymysql

from .config import load_env

DB_HOST = load_env("DB_HOST")
DB_USER = load_env("DB_USER")
DB_PASSWORD = load_env("DB_PASSWORD")
DB_DATABASE = load_env("DB_DATABASE")

logger = logging.getLogger(__name__)


class RepositoryService:
    def __init__(self):
        self.connection = pymysql.connect(
            host=DB_HOST,
            user=DB_USER,
            password=DB_PASSWORD,
            database=DB_DATABASE,
            cursorclass=pymysql.cursors.DictCursor,
        )
        self.cursor = self.connection.cursor()
        logger.debug("Connected to the database")

    def _create_brand(self, brand: str):
        qry = """
            INSERT INTO brands (name)
            VALUES (%s)
            ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id);
        """
        values = (brand,)
        self.cursor.execute(qry, values)
        self.connection.commit()
        logger.debug("Created brand %s", brand)
        return self.cursor.lastrowid

    def _create_category(self, category: str):
        qry = """
            INSERT INTO categories (name)
            VALUES (%s)
            ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id);
        """
        values = (category,)
        self.cursor.execute(qry, values)
        self.connection.commit()
        logger.debug("Created category %s", category)
        return self.cursor.lastrowid

    def _create_watch_images(self, watch_id, urls):
        qry = """
            INSERT INTO watch_images (watch_id, position, url)
            VALUES (%s, %s, %s)
        """
        for count, url in enumerate(urls):
            values = (watch_id, count, url)
            self.cursor.execute(qry, values)

    def _create_watch(self, watch: dict):
        category_id = self._create_category(watch["category"])
        brand_id = self._create_brand(watch["brand"])

        qry = """
            INSERT INTO watches (brand_id, supplier_id, category_id, name, price, description)
            VALUES (%s, %s, %s, %s, %s, %s)
        """
        values = (
            brand_id,
            1,
            category_id,
            watch["name"],
            watch["price"],
            watch["description"],
        )

        self.cursor.execute(qry, values)
        self._create_watch_images(self.cursor.lastrowid, watch["img_urls"])
        logger.debug("Added watch %s to db", watch["name"])

    def commit(self, watches: list[dict]):
        for watch in watches:
            self._create_watch(watch)

        self.connection.commit()
        logger.info("Added %s watches to the database", len(watches))

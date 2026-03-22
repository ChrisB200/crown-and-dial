import logging
import random
import sqlite3
from datetime import datetime, timezone

import pymysql

from .config import load_env

DB_TYPE = load_env("DB_TYPE") or "mysql"

DB_HOST = load_env("DB_HOST", "a")
DB_USER = load_env("DB_USER", "a")
DB_PASSWORD = load_env("DB_PASSWORD", "a")
DB_DATABASE = load_env("DB_DATABASE", "a")

logger = logging.getLogger(__name__)


class RepositoryService:
    def __init__(self):
        if DB_TYPE == "sqlite":
            self.connection = sqlite3.connect(DB_DATABASE)
            self.connection.row_factory = sqlite3.Row
            self.cursor = self.connection.cursor()
            self.placeholder = "?"
        else:
            self.connection = pymysql.connect(
                host=DB_HOST,
                user=DB_USER,
                password=DB_PASSWORD,
                database=DB_DATABASE,
                cursorclass=pymysql.cursors.DictCursor,
            )
            self.cursor = self.connection.cursor()
            self.placeholder = "%s"

        logger.debug("Connected to %s database", DB_TYPE)

    # ===== HELPERS =====

    def _insert_or_get(self, table: str, name: str):
        """
        Cross-db safe insert or get existing row id
        """
        if DB_TYPE == "mysql":
            qry = f"""
                INSERT INTO {table} (name)
                VALUES ({self.placeholder})
                ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id);
            """
            self.cursor.execute(qry, (name,))
            return self.cursor.lastrowid

        else:  # sqlite
            qry = f"""
                INSERT OR IGNORE INTO {table} (name)
                VALUES ({self.placeholder})
            """
            self.cursor.execute(qry, (name,))

            qry = f"""
                SELECT id FROM {table} WHERE name = {self.placeholder}
            """
            self.cursor.execute(qry, (name,))
            return self.cursor.fetchone()["id"]

    # ===== CREATE METHODS =====

    def _create_brand(self, brand: str):
        brand_id = self._insert_or_get("brands", brand)
        logger.debug("Created brand %s", brand)
        return brand_id

    def _create_category(self, category: str):
        category_id = self._insert_or_get("categories", category)
        logger.debug("Created category %s", category)
        return category_id

    def _create_watch_images(self, watch_id, urls):
        qry = f"""
            INSERT INTO watch_images (watch_id, position, url)
            VALUES ({self.placeholder}, {self.placeholder}, {self.placeholder})
        """
        for count, url in enumerate(urls):
            self.cursor.execute(qry, (watch_id, count, url))

    def _create_watch(self, watch: dict):
        category_id = self._create_category(watch["category"])
        brand_id = self._create_brand(watch["brand"])

        qry = f"""
            INSERT INTO watches (brand_id, supplier_id, category_id, name, price, description)
            VALUES ({self.placeholder}, {self.placeholder}, {self.placeholder},
                    {self.placeholder}, {self.placeholder}, {self.placeholder})
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

        watch_id = self.cursor.lastrowid
        self._create_watch_images(watch_id, watch["img_urls"])
        self._seed_random_inventory_sizes(watch_id)

        logger.debug("Added watch %s to db", watch["name"])

    def _seed_random_inventory_sizes(self, watch_id: int) -> None:
        """Create per-size rows with random stock (36–42mm) for Laravel watch_inventory_sizes."""
        ph = self.placeholder
        now = datetime.now(timezone.utc).strftime("%Y-%m-%d %H:%M:%S")
        sizes = (36, 38, 40, 42)
        qry = f"""
            INSERT INTO watch_inventory_sizes (watch_id, size, quantity, created_at, updated_at)
            VALUES ({ph}, {ph}, {ph}, {ph}, {ph})
        """
        for size in sizes:
            qty = random.randint(0, 25)
            self.cursor.execute(qry, (watch_id, size, qty, now, now))

    # ===== PUBLIC =====

    def commit(self, watches: list[dict]):
        for watch in watches:
            self._create_watch(watch)

        self.connection.commit()
        logger.info("Added %s watches to the database", len(watches))

import os

from dotenv import load_dotenv

load_dotenv()


def load_env(key: str, fallback=None):
    env = os.getenv(key)
    if env:
        return env

    if fallback:
        return fallback

    raise ValueError("Environment variable does not exist")

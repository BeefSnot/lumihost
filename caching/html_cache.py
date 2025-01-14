import redis
import uuid

# Connect to Redis using Unix socket
redis_client = redis.Redis(unix_socket_path='/home/lumihost/.redis/redis.sock', db=0)

def set_html_cache(page_url, html_content, expiration=None):
    """
    Cache HTML content for a specific page URL.
    :param page_url: The URL of the page.
    :param html_content: The HTML content to cache.
    :param expiration: Expiration time in seconds (optional).
    """
    unique_key = f"html_{page_url}_{uuid.uuid4()}"
    redis_client.set(name=unique_key, value=html_content, ex=expiration)
    return unique_key

def get_html_cache(page_url):
    """
    Get cached HTML content for a specific page URL.
    :param page_url: The URL of the page.
    :return: The cached HTML content if found, otherwise None.
    """
    keys = redis_client.keys(f"html_{page_url}_*")
    if keys:
        return redis_client.get(keys[0])
    return None

def delete_html_cache(page_url):
    """
    Delete cached HTML content for a specific page URL.
    :param page_url: The URL of the page.
    """
    keys = redis_client.keys(f"html_{page_url}_*")
    for key in keys:
        redis_client.delete(key)

# Example usage
if __name__ == "__main__":
    # Set HTML content in the cache with a 60-second expiration
    cache_key = set_html_cache('example_page', '<html>Example Content</html>', expiration=60)
    print(f"Cache key: {cache_key}")
    
    # Get the HTML content from the cache
    html_content = get_html_cache('example_page')
    if html_content:
        print(f'Cached HTML content: {html_content.decode("utf-8")}')
    else:
        print('HTML content not found in cache')
    
    # Delete the HTML content from the cache
    delete_html_cache('example_page')
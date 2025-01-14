import redis
import uuid

# Connect to Redis using Unix socket
redis_client = redis.Redis(unix_socket_path='/home/lumihost/.redis/redis.sock', db=0)

def set_cache(key, value, expiration=None):
    """
    Set a value in the cache with an optional expiration time.
    :param key: The key under which the value is stored.
    :param value: The value to store.
    :param expiration: Expiration time in seconds (optional).
    """
    unique_key = f"{key}_{uuid.uuid4()}"
    redis_client.set(name=unique_key, value=value, ex=expiration)
    return unique_key

def get_cache(key):
    """
    Get a value from the cache.
    :param key: The key of the value to retrieve.
    :return: The value if found, otherwise None.
    """
    return redis_client.get(name=key)

def delete_cache(key):
    """
    Delete a value from the cache.
    :param key: The key of the value to delete.
    """
    redis_client.delete(name=key)

# Example usage
if __name__ == "__main__":
    # Set a value in the cache with a 60-second expiration
    cache_key = set_cache('example_key', 'example_value', expiration=60)
    print(f"Cache key: {cache_key}")
    
    # Get the value from the cache
    value = get_cache(cache_key)
    if value:
        print(f'Cached value: {value.decode("utf-8")}')
    else:
        print('Value not found in cache')
    
    # Delete the value from the cache
    delete_cache(cache_key)
import redis
import uuid

# Connect to Redis using Unix socket
redis_client = redis.Redis(unix_socket_path='/home/lumihost/.redis/redis.sock', db=0)

def set_api_cache(api_url, response_data, expiration=None):
    """
    Cache API response data for a specific API URL.
    :param api_url: The URL of the API.
    :param response_data: The API response data to cache.
    :param expiration: Expiration time in seconds (optional).
    """
    unique_key = f"api_{api_url}_{uuid.uuid4()}"
    redis_client.set(name=unique_key, value=response_data, ex=expiration)
    return unique_key

def get_api_cache(api_url):
    """
    Get cached API response data for a specific API URL.
    :param api_url: The URL of the API.
    :return: The cached API response data if found, otherwise None.
    """
    keys = redis_client.keys(f"api_{api_url}_*")
    if keys:
        return redis_client.get(keys[0])
    return None

def delete_api_cache(api_url):
    """
    Delete cached API response data for a specific API URL.
    :param api_url: The URL of the API.
    """
    keys = redis_client.keys(f"api_{api_url}_*")
    for key in keys:
        redis_client.delete(key)

# Example usage
if __name__ == "__main__":
    # Set API response data in the cache with a 60-second expiration
    cache_key = set_api_cache('example_api', '{"data": "example"}', expiration=60)
    print(f"Cache key: {cache_key}")
    
    # Get the API response data from the cache
    response_data = get_api_cache('example_api')
    if response_data:
        print(f'Cached API response data: {response_data.decode("utf-8")}')
    else:
        print('API response data not found in cache')
    
    # Delete the API response data from the cache
    delete_api_cache('example_api')
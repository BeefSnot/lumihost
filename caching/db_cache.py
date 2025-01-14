import redis
import uuid

# Connect to Redis using Unix socket
redis_client = redis.Redis(unix_socket_path='/home/lumihost/.redis/redis.sock', db=0)

def set_db_cache(query, result_data, expiration=None):
    """
    Cache database query results.
    :param query: The database query.
    :param result_data: The query result data to cache.
    :param expiration: Expiration time in seconds (optional).
    """
    unique_key = f"db_{query}_{uuid.uuid4()}"
    redis_client.set(name=unique_key, value=result_data, ex=expiration)
    return unique_key

def get_db_cache(query):
    """
    Get cached database query results.
    :param query: The database query.
    :return: The cached query result data if found, otherwise None.
    """
    keys = redis_client.keys(f"db_{query}_*")
    if keys:
        return redis_client.get(keys[0])
    return None

def delete_db_cache(query):
    """
    Delete cached database query results.
    :param query: The database query.
    """
    keys = redis_client.keys(f"db_{query}_*")
    for key in keys:
        redis_client.delete(key)

# Example usage
if __name__ == "__main__":
    # Set database query result data in the cache with a 60-second expiration
    cache_key = set_db_cache('SELECT * FROM example_table', '[{"id": 1, "name": "example"}]', expiration=60)
    print(f"Cache key: {cache_key}")
    
    # Get the database query result data from the cache
    result_data = get_db_cache('SELECT * FROM example_table')
    if result_data:
        print(f'Cached query result data: {result_data.decode("utf-8")}')
    else:
        print('Query result data not found in cache')
    
    # Delete the query result data from the cache
    delete_db_cache('SELECT * FROM example_table')
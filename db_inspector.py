import pymysql
import json
import sys

def inspect_db():
    print("Connecting to independent database session...")
    try:
        connection = pymysql.connect(
            host='localhost',
            user='root',
            password='',
            database='dynabio',
            cursorclass=pymysql.cursors.DictCursor
        )
        
        with connection.cursor() as cursor:
            cursor.execute("SHOW TABLES")
            tables = cursor.fetchall()
            print("\n=== DATABASE TABLES ===")
            for table in tables:
                table_name = list(table.values())[0]
                print(f"- {table_name}")
                
            print("\n=== USERS TABLE SNEAK PEEK (First 3 Active Users) ===")
            cursor.execute("SELECT user_id, username, email, role FROM users LIMIT 3")
            users = cursor.fetchall()
            for user in users:
                print(f"[{user['user_id']}] {user['username']} ({user['role']}) - {user['email']}")
                
            print("\n=== PEEK AT SITE OWNER'S GITHUB CACHE ===")
            cursor.execute("SELECT user_id, updated_at FROM github_cache LIMIT 1")
            cache = cursor.fetchone()
            if cache:
                print(f"Github Cache exists for User ID {cache['user_id']}. Last updated: {cache['updated_at']}")
            else:
                print("No GitHub cache found.")
                
        connection.close()
        print("\nSession cleanly disconnected.")
    except Exception as e:
        print(f"Error querying database: {e}")

if __name__ == '__main__':
    inspect_db()

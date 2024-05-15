import mysql.connector
from mysql.connector import Error

def create_connection():
    try:
        conn = mysql.connector.connect(
            host='localhost',
            user='root',
            password=''  
        )
        if conn.is_connected():
            print('Connected to MySQL database')
        return conn
    except Error as e:
        print(e)

def create_database(cursor, database_name):
    try:
        cursor.execute(f"CREATE DATABASE {database_name}")
        print(f"Database {database_name} created successfully")
    except Error as e:
        print(e)

def create_tables(cursor):
    commands = [
        """
        CREATE TABLE Users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            is_admin BOOLEAN NOT NULL DEFAULT FALSE,
            UNIQUE (email),
            COUNTY VARCHAR(255) NOT NULL,
            CITY VARCHAR(255) NOT NULL
        )
        """,
        """
        CREATE TABLE Events (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            date DATE NOT NULL,
            location VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            image_url VARCHAR(255) NOT NULL
        )
        """,
        """
        CREATE TABLE User_Events (
            user_id INT,
            event_id INT,
            PRIMARY KEY (user_id, event_id),
            FOREIGN KEY (user_id) REFERENCES Users(id),
            FOREIGN KEY (event_id) REFERENCES Events(id)
        )
        """
    ]
    try:
        for command in commands:
            cursor.execute(command)
        print("Tables created successfully")
    except Error as e:
        print(e)

def main():
    database_name = 'ConnectFestDB'  

    conn = create_connection()  
    if conn:
        cursor = conn.cursor()
        create_database(cursor, database_name)  
        conn.close()

    conn = mysql.connector.connect(
        host='localhost',
        user='root',
        password='',  
        database=database_name
    )
    if conn.is_connected():
        cursor = conn.cursor()
        create_tables(cursor)  
        conn.close()

if __name__ == '__main__':
    main()

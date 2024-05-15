import mysql.connector
from mysql.connector import Error
from passlib.hash import sha256_crypt
def create_connection():
    try:
        conn = mysql.connector.connect(
            host='localhost',
            user='root',
            password='',  
            database='ConnectFestDB'
        )
        if conn.is_connected():
            print('Connected to MySQL database')
        return conn
    except Error as e:
        print(e)

def insert_users(cursor):
    query = """
    INSERT INTO Users (username, password, email, is_admin)
    VALUES (%s, %s, %s, %s)
    """
    
    users = [
        ('Alice', sha256_crypt.hash('123'), 'alice@example.com', False),
        ('Bob', sha256_crypt.hash('123'), 'bob@example.com', False),
        ('Charlie', sha256_crypt.hash('123'), 'charlie@example.com', True),
        ('David', sha256_crypt.hash('123'), 'david@example.com', False),
        ('Eva', sha256_crypt.hash('123'), 'eva@example.com', True)
    ]
    try:
        cursor.executemany(query, users)
        print("Users inserted successfully")
    except Error as e:
        print(e)

def insert_events(cursor):
    query = """
    INSERT INTO Events (title, date, location, description, image_url)
    VALUES (%s, %s, %s, %s, %s)
    """
    events = [
        ('Community Cleanup', '2024-06-15', 'Central Park', 'Join us for a community cleanup day.', 'https://picsum.photos/200'),
        ('Tech Conference', '2024-07-20', 'Convention Center', 'Annual tech conference featuring industry experts.', 'https://picsum.photos/200'),
        ('Live Concert', '2024-08-05', 'Downtown Arena', 'Experience an electrifying live concert.', 'https://picsum.photos/200'),
        ('Art Exhibition', '2024-09-10', 'City Gallery', 'Explore modern art from local artists.', 'https://picsum.photos/200'),
        ('Food Festival', '2024-10-23', 'Riverfront Park', 'Taste dishes from the world’s best chefs.', 'https://picsum.photos/200'),
        ('Book Fair', '2024-11-15', 'Library Center', 'Discover new authors and books.', 'https://picsum.photos/200'),
        ('Marathon', '2024-12-04', 'Main Street', 'Participate in our annual city marathon.', 'https://picsum.photos/200'),
        ('Science Fair', '2025-01-18', 'Science Museum', 'Engage with the latest in scientific research.', 'https://picsum.photos/200'),
        ('Music Workshop', '2025-02-12', 'Music Hall', 'Learn from experienced musicians.', 'https://picsum.photos/200'),
        ('Gardening Seminar', '2025-03-19', 'Community Center', 'Enhance your gardening skills.', 'https://picsum.photos/200'),
        ('Yoga Retreat', '2025-04-25', 'Mountain Resort', 'Relax and rejuvenate with yoga.', 'https://picsum.photos/200'),
        ('Film Festival', '2025-05-30', 'Cinema Center', 'Watch the best in independent cinema.', 'https://picsum.photos/200'),
        ('Fashion Show', '2025-06-15', 'Fashion Mall', 'Experience the latest in fashion trends.', 'https://picsum.photos/200'),
        ('Dance Workshop', '2025-07-20', 'Dance Studio', 'Learn new dance styles.', 'https://picsum.photos/200'),
        ('Wine Tasting', '2025-08-05', 'Vineyard', 'Sample wines from around the world.', 'https://picsum.photos/200'),
        ('Craft Fair', '2025-09-10', 'Craft Market', 'Shop for unique handmade crafts.', 'https://picsum.photos/200'),
    ]

    counter = 0
    for event in events:
        event = list(event)
        event[4] += f'?{counter}'
        events[counter] = tuple(event)
        counter += 1

    try:
        cursor.executemany(query, events)
        print("Events inserted successfully")
    except Error as e:
        print(e)

def insert_user_events(cursor):
    query = """
    INSERT INTO User_Events (user_id, event_id)
    VALUES (%s, %s)
    """
    user_events = [
        (1, 1),
        (1, 3),
        (2, 2),
        (3, 1),
        (3, 2),
        (3, 3),
        (4, 4),
        (4, 5),
        (5, 6),
        (5, 7),
        (1, 8),
        (2, 9),
        (3, 10)
    ]
    try:
        cursor.executemany(query, user_events)
        print("User-Event relationships inserted successfully")
    except Error as e:
        print(e)

def main():
    conn = create_connection()
    if conn:
        cursor = conn.cursor()
        insert_users(cursor)       
        insert_events(cursor)      
        insert_user_events(cursor) 
        conn.commit()              
        cursor.close()
        conn.close()

if __name__ == '__main__':
    main()

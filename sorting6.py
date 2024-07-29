import mysql.connector

# MySQL database connection parameters
db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'examseater'
}

# Connect to MySQL database
conn = mysql.connector.connect(**db_config)
cursor = conn.cursor()

try:
    # Truncate the allot table
    cursor.execute("TRUNCATE TABLE allot")

    # Fetch student_code, year, dept, division from examstudents joined with class for strength
    query = """
        SELECT es.student_code, es.year, es.dept, es.division, c.strength
        FROM examstudents es
        JOIN class c ON es.class_id = c.class_id
    """
    cursor.execute(query)
    rows = cursor.fetchall()

    # Dictionary to store data grouped by year and department
    data_by_year_dept = {}

    # Organize data by year and department, sorted by strength descending
    for row in rows:
        student_code = row[0]
        year = row[1]
        dept = row[2]
        division = row[3]
        strength = row[4]

        if year not in data_by_year_dept:
            data_by_year_dept[year] = {}

        if dept not in data_by_year_dept[year]:
            data_by_year_dept[year][dept] = []

        data_by_year_dept[year][dept].append((student_code, strength))

    # Sort student_codes based on strength for each year and department
    sorted_student_codes = []
    for year in sorted(data_by_year_dept.keys()):
        for dept in sorted(data_by_year_dept[year].keys(), key=lambda x: sum(s[1] for s in data_by_year_dept[year][x]), reverse=True):
            sorted_student_codes.extend(sc[0] for sc in data_by_year_dept[year][dept])

    # Fetch rooms data
    query_rooms = "SELECT room_no, building, bench_row, bench_column FROM room"
    cursor.execute(query_rooms)
    rooms = cursor.fetchall()

    # Create seating matrices for each room
    seating_matrices = []
    for room in rooms:
        room_no = room[0]
        building = room[1]
        bench_row = room[2]
        bench_column = room[3]

        # Calculate matrix dimensions
        matrix_rows = bench_row
        matrix_columns = 2 * bench_column

        # Initialize seating matrix with 0 and 1 pattern
        seating_matrix = [[0 if col % 2 == 0 else 1 for col in range(matrix_columns)] for _ in range(matrix_rows)]
        seating_matrices.append((room_no, building, seating_matrix))

    # Fill zeros in matrices first, vertically
    student_index = 0
    for _, _, seating_matrix in seating_matrices:
        for col in range(len(seating_matrix[0])):
            for row in range(len(seating_matrix)):
                if seating_matrix[row][col] == 0 and student_index < len(sorted_student_codes):
                    seating_matrix[row][col] = sorted_student_codes[student_index]
                    student_index += 1

    # Fill ones in matrices next, vertically
    for _, _, seating_matrix in seating_matrices:
        for col in range(len(seating_matrix[0])):
            for row in range(len(seating_matrix)):
                if seating_matrix[row][col] == 1 and student_index < len(sorted_student_codes):
                    seating_matrix[row][col] = sorted_student_codes[student_index]
                    student_index += 1

    # Insert seating arrangement into allot table
    insert_query = """
        INSERT INTO allot (room_no, building, row_number, column_number, student_id, student_code)
        VALUES (%s, %s, %s, %s, %s, %s)
    """

    for room_no, building, seating_matrix in seating_matrices:
        for row in range(len(seating_matrix)):
            for col in range(len(seating_matrix[0])):
                student_code = seating_matrix[row][col]
                if student_code != 0 and student_code != 1:
                    # Fetch student_id using student_code
                    query_student_id = "SELECT student_id FROM examstudents WHERE student_code = %s"
                    cursor.execute(query_student_id, (student_code,))
                    student_id = cursor.fetchone()[0]

                    # Insert into allot table
                    cursor.execute(insert_query, (room_no, building, row, col, student_id, student_code))

    # Commit the transaction
    conn.commit()

    # Print seating arrangement for each room
    for room_no, _, seating_matrix in seating_matrices:
        print(f"\nSeating arrangement for Room: {room_no}")
        for row in seating_matrix:
            print(" ".join(f"{str(cell):<10}" for cell in row))

finally:
    cursor.close()
    conn.close()

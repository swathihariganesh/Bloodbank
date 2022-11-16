import mysql.connector, csv

# --- MYSQL configuration -----
host = "127.0.0.1"
username = "root"
password = ""
database_name = "AMS"
table_name = "students"
# -----------------------------

myDb = mysql.connector.connect(
        host=host,
        user=username,
        database=database_name
    )
    
myCursor = myDb.cursor()

print("Connection successfull!")

query = "TRUNCATE TABLE `"+table_name+"`"
myCursor.execute(query)
myDb.commit()
print("Table deleted")

data = []

with open('STUDENTS.csv', 'r') as file:
    csvReader = csv.reader(file)
    for row in csvReader:
        data.append(row)

query = "INSERT INTO `"+table_name+"` ("+"`roll_no`, `name`, `marks`"+") VALUES ("+','.join(["%s" for s in range(3)])+")"  
print("Query: " + query)

myCursor.executemany(query, data)
myDb.commit()
print("Total", len(data), "data added to table!")

# print(data)
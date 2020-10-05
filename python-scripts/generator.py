# /bin/sh python3

'''
Title: generator.py
Description: This script will generate random data we need for testing purposes
Usage: To be used in creating new patient identification numbers, DOB's, and names.
import documentation: random - https://docs.python.org/3/library/random.html#module-random
argparse - https://docs.python.org/3/library/argparse.html?highlight=argparse#module-argparse
faker - https://faker.readthedocs.io/en/master/index.html
'''

# imports
import mysql.connector as mysql
import random
import argparse
from faker import Faker
from dateutil.relativedelta import relativedelta
import datetime as date

# main like in C/C++
if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="random-id-gen.py")
    parser.add_argument("-a", type=int, required=True,
                        dest="amount", help="number of ID's to generate")
    parser.add_argument("-v", default=False, action="store_true", 
                        dest="verbose", help="verbose output flag")
    parser.add_argument("-patients", default=False, action="store_true",
                        dest="patients", help="Set if you need to insert into Patients table")
    parser.add_argument("-host", type=str, required=False, default=None,
                        dest="host", help="Hostname for DB")
    parser.add_argument("-user", type=str, required=False, default=None,
                        dest="user", help="Username for DB")
    parser.add_argument("-pass", type=str, required=False, default=None,
                        dest="password", help="Password for DB")
    parser.add_argument("-db", type=str, required=False, default=None,
                        dest="database", help="Database to connect too")
    args = parser.parse_args()

    if args.host:
        # Connect to our MySQL server.
        mydb = mysql.connect(host=args.host, user=args.user,
                             password=args.password, database=args.database)
        mycursor = mydb.cursor()

    # SQL Statements for tables
    insert_patients = "INSERT INTO PatientInfo(PID, name_first, name_last, DOB, gender, address, email, phone, Emergency_name, Emergency_phone) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"

    # Start the Faker instance
    random.seed()
    fake = Faker()

    for i in range(0, args.amount):
        try:

            # Let's generate all the data first
            # Must be a string to Zero Pad, 9223372036854775807 is bigint unsided MySQL max value
            ran_ID = str(random.randint(0, 9223372036854775807))
            # Print and Zero Pad if needed.
            ran_ID = ran_ID.zfill(20)
            # Birthdays. Lets make them in MySQL format
            ran_DOB = fake.date(pattern='%Y-%m-%d', end_datetime=None)
            # Calculate the Age
            today = date.datetime.today()
            age = relativedelta(today, date.datetime.strptime(ran_DOB, "%Y-%m-%d"))
            ran_AGE = age.years
            if i % 2 == 0:
                ran_FNAME = fake.first_name_male()
                ran_LNAME = fake.last_name_male()
                ran_GENDER = "male"
            else:
                ran_FNAME = fake.first_name_female()
                ran_LNAME = fake.last_name_female()
                ran_GENDER = "female"
            
            ran_ENAME = fake.name()
            ran_PHONE = fake.phone_number()
            ran_EPHONE = fake.phone_number()
            ran_ADDRESS = fake.address()
            ran_COMPANY_NAME = fake.company()
            ran_EMAIL = fake.email()
            

            if args.verbose:
                print(ran_ID)
                print(ran_DOB)
                print(ran_AGE)
                print(ran_FNAME)
                print(ran_LNAME)
                print(ran_GENDER)
                print(ran_PHONE)
                print(ran_ADDRESS)
                print(ran_COMPANY_NAME)
                print(ran_EMAIL)

             # Insert data into Patients
            if args.host and args.user and args.password and args.database and args.patients:
                mycursor.execute(insert_patients,
                                (int(ran_ID),
                                ran_FNAME,
                                ran_LNAME,
                                ran_DOB,
                                ran_GENDER,
                                ran_ADDRESS,
                                ran_EMAIL,
                                ran_PHONE,
                                ran_ENAME,
                                ran_EPHONE))
                mydb.commit()
        except KeyboardInterrupt:
            mydb.commit()
            mydb.close()

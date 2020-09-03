# /bin/sh python3

'''
Title: random-id-gen.py
Description: This script will generate random identification numbers of length 20.
Usage: To be used in creating new patient identification numbers.
import documentation: random - https://docs.python.org/3/library/random.html#module-random
argparse - https://docs.python.org/3/library/argparse.html?highlight=argparse#module-argparse
faker - https://faker.readthedocs.io/en/master/index.html
'''

# imports
import random
import argparse
from faker import Faker
from faker.providers.date_time import date

# main like in C/C++
if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="random-id-gen.py")
    parser.add_argument("-a", type=int, required=True,
                        dest="amount", help="number of ID's to generate")
    parser.add_argument("-id", default=False, action="store_true",
                        dest="id", help="Set if you need ID's generated")
    parser.add_argument("-b", default=False, action="store_true",
                        dest="birthday", help="Set if you need birthdays generated")
    args = parser.parse_args()
    random.seed()

    if args.id:
        # Print ID's
        for i in range(0, args.amount):
            # Must be a string to Zero Pad
            ran_ID = str(random.randint(0, 1e20))
            # Print and Zero Pad if needed.
            print(ran_ID.zfill(20))

    if args.birthday:
        # Print Birthday's and init class
        fake = Faker()
        for i in range(0, args.amount):
            print(fake.date(pattern='%d-%m-%Y', end_datetime=None))

"""
senddata.py

Description:
Parking lot sensor (camera) server communication module.

Used to send update data about changed in the car parking spaces
to a central server.

"""
import urllib
import urllib2
import json
import data.settings as s
import pyodbc

def send_update(status_id, space_id):

    cnxn = pyodbc.connect('DSN=psvdDSN')
    cursor = cnxn.cursor()

    # Update Query
    print ('Updating Status for Space', space_id)
    tsql = "UPDATE spaces SET space_status_id = ? WHERE space_id = ?"
    with cursor.execute(tsql,status_id,space_id):
        print ('Successfuly Updated!')

if __name__ == "__main__":
    # Example for sending updates
    for i in range(0,5):
        j = send_update(i, 1)
        print j

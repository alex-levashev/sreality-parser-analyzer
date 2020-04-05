#!/usr/bin/python
# -*- coding: utf-8 -*-

import json
import urllib
import sys
import pymongo

client = pymongo.MongoClient("mongodb://localhost:27017/", username='root', password='root')
db = client["real_estate"]
col = db["raw_requests"]

def days_between(d1, d2):
    d1 = datetime.strptime(d1, "%Y-%m-%d")
    return abs((d2 - d1).days)

# a = 0
# for x in col.find():
    # a += 1
# print(str(a) + ' records ...')


# query = {"$and" : [ {"items.name": "Aktualizace"}, {"items.value": "V era" } ] }
# query = {"items.name": "Aktualizace"}


# x = col.delete_many(query)
# print(x.deleted_count, " documents deleted.")
a = 0
for x in col.find():
    a += 1
    for y in x['items']:
        if("Vybaven" in y['name']):
            print(str(a) + ' - ' + y['value'].encode('utf-8'))
# print(x.count(), " documents deleted.")

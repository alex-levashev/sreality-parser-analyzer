#!/usr/bin/python
# -*- coding: utf-8 -*-

import json
import urllib
import sys
import pymongo

client = pymongo.MongoClient("mongodb://localhost:27017/", username='root', password='root')
db = client["real_estate"]
col = db["raw_requests"]

col.drop()
print('DB Dropped ...')

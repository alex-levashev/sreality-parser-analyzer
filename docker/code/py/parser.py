#!/usr/bin/python
# -*- coding: utf-8 -*-

import json
import urllib
import sys
import pymongo
from datetime import datetime, timedelta


request = [
'category_type_cb=1',
'category_main_cb=1',
'ownership=1',
'czk_price_summary_order2=1000000-10000000',
'estate_age=0',
'locality_district_id=5001,5002,5003,5004,5005,5006,5007,5008,5009,5010'
]

per_page = 200

def FilterData( request, per_page, page_number):
    url = ("https://www.sreality.cz/api/cs/v2/estates?" + '&'.join(request) + '&per_page=' + str(per_page) + '&page=' + str(page_number)).replace('-','%7C').replace(',','%7C')
    response = urllib.urlopen(url)
    data = json.loads(response.read())
    return(data)

def ItemData ( id ):
    url = "https://www.sreality.cz/api/cs/v2/estates/" + str(id)
    response = urllib.urlopen(url)
    data = json.loads(response.read())
    for item in data['items']:
        if(item['name'] == 'Aktualizace' and item['value'] == 'Dnes'):
            item['value'] = datetime.today().strftime("%d.%m.%Y")
        elif(item['name'] == 'Aktualizace' and  item['value'].encode('utf-8') == 'Včera'):
            item['value'] = (datetime.today() - timedelta(days=1)).strftime("%d.%m.%Y")
    return(data)

## DB

client = pymongo.MongoClient("mongodb://localhost:27017/", username='root', password='root')
db = client["real_estate"]
col = db["raw_requests"]

total_number = FilterData(request, 1, 1)['result_size']

total_number_of_pages = total_number/per_page
if(total_number == 0):
    print('Exiting ...')
    sys.exit()

page_number = 1
n = 0
if(total_number_of_pages <= 0):
    houses = FilterData(request, per_page, 1)['_embedded']['estates']
    for item in houses:
        n += 1
        tmp = ItemData(item['hash_id'])
        record = col.update({'_embedded.favourite._links.self.href': tmp['_embedded']['favourite']['_links']['self']['href']}, tmp, upsert=True)
else:
    while(page_number < (total_number_of_pages + 2)):
        houses = FilterData(request, per_page, page_number)['_embedded']['estates']
        for item in houses:
            n += 1
            tmp = ItemData(item['hash_id'])
            record = col.update({'_embedded.favourite._links.self.href': tmp['_embedded']['favourite']['_links']['self']['href']}, tmp, upsert=True)
        page_number += 1
print('Script finished successfully ...')

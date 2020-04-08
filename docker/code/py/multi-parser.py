#!/usr/bin/python
# -*- coding: utf-8 -*-

import json
import urllib
import sys
import pymongo
from datetime import datetime, timedelta

from multiprocessing.dummy import Pool as ThreadPool


request = [
'category_type_cb=1',
'category_main_cb=1',
'ownership=1',
'czk_price_summary_order2=4990000-5000000',
'estate_age=1',
'locality_district_id=5001,5002,5003,5004,5005,5006,5007,5008,5009,5010'
]

per_page = 999

def get_list_of_items_by_request( request, per_page, page_number):
    url = ("https://www.sreality.cz/api/cs/v2/estates?" + '&'.join(request) + '&per_page=' + str(per_page) + '&page=' + str(page_number)).replace('-','%7C').replace(',','%7C')
    response = urllib.urlopen(url)
    data = json.loads(response.read())
    return(data)

def get_item_data_by_id ( id ):
    url = "https://www.sreality.cz/api/cs/v2/estates/" + str(id)
    response = urllib.urlopen(url)
    data = json.loads(response.read())
    for item in data['items']:
        if(item['name'] == 'Aktualizace' and item['value'] == 'Dnes'):
            item['value'] = datetime.today().strftime("%d.%m.%Y")
        elif(item['name'] == 'Aktualizace' and  item['value'].encode('utf-8') == 'Včera'):
            item['value'] = (datetime.today() - timedelta(days=1)).strftime("%d.%m.%Y")
    data['parsed'] = { 'date': datetime.today().strftime("%d.%m.%Y"), 'id': id, 'price': { datetime.today().strftime("%d-%m-%Y") : data['price_czk']['value_raw'] } }
    data.pop('poi', None)
    data['_embedded'].pop('images', None)
    if('seller' in data['_embedded'] and 'user_id' in data['_embedded']['seller']):
        if(data['_embedded']['seller']['user_id'] != ''):
            tmp = data['_embedded']['seller']['user_id']
            data['_embedded']['seller'].clear()
            data['_embedded']['seller']['user_id'] = tmp;
    else:
        data['_embedded']['seller'] = {'user_id':'NA'}
    return(data)

def record_item_to_db ( item_id ):
    item_from_request = get_item_data_by_id(item_id['hash_id'])
    item_from_db = col.find_one({"parsed.id":item_from_request['parsed']['id']})
    if(item_from_db is None):
        col.insert_one(item_from_request)
    elif(item_from_db is not None and item_from_request['price_czk']['value_raw'] != item_from_db['price_czk']['value_raw']):
        col.update_one({"parsed.id":item_from_request['parsed']['id']}, { "$set": { "price_czk.value_raw" : item_from_request['price_czk']['value_raw'], "parsed.price." + str(datetime.today().strftime("%d-%m-%Y")): item_from_db['price_czk']['value_raw']  }  })
    return



## DB
start_time = datetime.now()
print('Script starts at ' + start_time.strftime("%Y-%m-%d %H:%M:%S"))
client = pymongo.MongoClient("mongodb://localhost:27017/", username='root', password='root')
db = client["real_estate"]
col = db["raw_requests"]

total_number = get_list_of_items_by_request(request, 1, 1)['result_size']
print('Totally ' + str(total_number) + ' items found.')

total_number_of_pages = total_number/per_page
if(total_number == 0):
    print('Exiting ... Nothing to store')
    print('Script ends at ' + end_time.strftime("%Y-%m-%d %H:%M:%S") + ' run for ' + str((end_time-start_time).total_seconds()) + ' seconds.')
    sys.exit()

pool = ThreadPool(20)

if(total_number_of_pages <= 0):
    houses = get_list_of_items_by_request(request, per_page, 1)['_embedded']['estates']
    results = pool.map(record_item_to_db, houses)
    pool.close()
    pool.join()

else:
    while(total_number_of_pages >= 0):
        houses = get_list_of_items_by_request(request, per_page, total_number_of_pages+1)['_embedded']['estates']
        results = pool.map(record_item_to_db, houses)
        pool.close()
        pool.join()
        total_number_of_pages -= 1

print('Totally ' + str(total_number) + ' items found.')
print('Script finished successfully ...')
end_time = datetime.now()
print('Script ends at ' + end_time.strftime("%Y-%m-%d %H:%M:%S") + ' run for ' + str((end_time-start_time).total_seconds()) + ' seconds.')

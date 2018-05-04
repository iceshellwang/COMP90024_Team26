import geopandas as gpd
from shapely.geometry import Point, Polygon
import json


melbourne = gpd.read_file('Simplify_Melbourne_SA2.geojson')

melbourne_sa2_list = list(melbourne.SA2_NAME16)
results = {}
with open('place.txt') as json_data:
  rows = json.load(json_data)['rows']
  for row in rows:
    key = row['key'].split(',')[0].strip()
    results[key] = []
    keys = key.split()
    flag = False
    for item in melbourne_sa2_list:
      if key == item:
        results[key].append(item)
        flag = True
    if not flag:
      for item in melbourne_sa2_list:
        if key in item:
          results[key].append(item)
          flag = True
    if not flag:
      for item in melbourne_sa2_list:
        for temp_key in keys:
          if temp_key.isalpha() and temp_key in item:
            results[key].append(item)
    results[key] = set(results[key])

for key, value in results.items():
  if len(value) == 1:
    print(key, "//", list(value)[0])






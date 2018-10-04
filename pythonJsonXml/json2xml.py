import json
import xmltodict

with open('sample.json', 'r') as f:
    jsonString = f.read()

print('JSON input (sample.json):')
print(jsonString)

xmlString = xmltodict.unparse(json.loads(jsonString), pretty=True)

print('\nXML output(outputJSON2XML.xml):')
print(xmlString)

with open('outputJSON2XML.xml', 'w') as f:
    f.write(xmlString)
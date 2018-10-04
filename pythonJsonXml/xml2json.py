import json
import xmltodict

with open("sample.xml", 'r') as f:
    xmlString = f.read()

print("XML input (sample.xml):")
print(xmlString)

jsonString = json.dumps(xmltodict.parse(xmlString), indent=4)

print("\nJSON output(outputXML2JSON.json):")
print(jsonString)

with open("outputXML2JSON.json", 'w') as f:
    f.write(jsonString)
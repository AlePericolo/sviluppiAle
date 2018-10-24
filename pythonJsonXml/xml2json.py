import json
import xmltodict

with open("xml/fattura.xml", 'r') as f:
    xmlString = f.read()

    jsonString = json.dumps(xmltodict.parse(xmlString), indent=4)

    with open("json/fattura.json", 'w') as f:
        f.write(jsonString)
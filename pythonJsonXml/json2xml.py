import json
import xmltodict

with open('json/fattura.json', 'r') as f:
    jsonString = f.read()

    xmlString = xmltodict.unparse(json.loads(jsonString), pretty=True)

    with open('xml/fattura2.xml', 'w') as f:
        f.write(xmlString)
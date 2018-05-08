# COMP90024_Team26

This the assignment to of COMP90024 for Team 26.


# Files

- Auto_Config
The Auto_Config folder includes the Boto and Ansible code to auto configuration all the NeCTAR instances or EC2 instances.
- Geo_File
The Geo_File folder includes the shape file or the geojson file that can be loaded into QGIS and combine with the aurin and ABS data to generate a new geojson.
- html
The html floder includes the html css and php code for web server.
- Python_code
The Python_code folder includes all the original testing python code for harversting, sentiment analysis, sa2 area assign and all other code. In the Final folder, it is the final version of code that auto-deploying on the cloud platform to run.

# How to use

- Download the github repository https://github.com/zjw93615/COMP90024_Team26
- Open the COMP90024_Team26/Auto_Config/Boto folder
- Run launch_instance.py
- Goto COMP90024_Team26/Auto_Config/Ansible/couchdb
- Open the hosts file and config the CouchDB and cluster and write down IP address of your instances
- Run command line ansible-playbook -i hosts -u ubuntu --key-file=cloud.key main.yml

After running all the Ansible script, you instances should be up and processing data, and the web application also available. 

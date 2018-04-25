from nectar import ec2_conn
import time

INSTANCES_NUMBER = 3


def create_default_couchDB_security_group():
  print "Creating couchDB security group"
  security_group = ec2_conn.create_security_group("default_couchDB",
                                                    "default security group for couchDB")

  security_group.authorize(ip_protocol='tcp',
                                      from_port=5984,
                                      to_port=5984,
                                      cidr_ip="0.0.0.0/0")
  security_group.authorize(ip_protocol='udp',
                                      from_port=5984,
                                      to_port=5984,
                                      cidr_ip="0.0.0.0/0")
  print "Successfully created"

def create_default_http_https_group():
  print "Creating http/https security group"
  security_group = ec2_conn.create_security_group("default_HTTP/HTTPS",
                                                    "default security group for http and https")

  security_group.authorize(ip_protocol='tcp',
                                      from_port=80,
                                      to_port=80,
                                      cidr_ip="0.0.0.0/0")
  security_group.authorize(ip_protocol='tcp',
                                      from_port=443,
                                      to_port=443,
                                      cidr_ip="0.0.0.0/0")
  print "Successfully created"

def create_default_ssh_group():
  print "Creating ssh security group"
  security_group = ec2_conn.create_security_group("default_SSH",
                                                    "default security group for ssh")

  security_group.authorize(ip_protocol='tcp',
                                      from_port=22,
                                      to_port=22,
                                      cidr_ip="0.0.0.0/0")
  print "Successfully created"


def set_security_groups():
  try:
    create_default_couchDB_security_group()
  except Exception as e:
    print e
    print "Cannot create security group"
  try:
    create_default_http_https_group()
  except Exception as e:
    print e
    print "Cannot create security group"
  try:
    create_default_ssh_group()
  except Exception as e:
    print e
    print "Cannot create security group"

def create_instances(num = 1):
  print "Creating instances"
  reservation = ec2_conn.run_instances('ami-a9d574bc',
                                        key_name='Jiawei',
                                        min_count=1,
                                        max_count=num,
                                        instance_type='m1.small',
                                        placement="melbourne-np",
                                        security_groups=['default_couchDB','default_HTTP/HTTPS','default_SSH'])
  print "Successfully created"
  return reservation

def wait_instances_started(reservation):
  for instance in reservation.instances:
    while instance.state != "running":
      print "Instance " + instance.id + " is " + instance.state
      time.sleep(5)
      instance.update()

def create_volumes(num):
  print "Creating volumes"
  volumes = []
  for i in range(num):
    volume = ec2_conn.create_volume(30, "melbourne-np")
    volumes.append(volume)
  print "Successfully Created"
  return volumes

def attach_volumes(reservation, volumes):
  print "Attaching volumes"
  for i in range(len(reservation.instances)):
    ec2_conn.attach_volume(volumes[i].id, reservation.instances[i].id, "/dev/vdc")
  print "Successfully Attached"

if __name__ == '__main__':
  set_security_groups()
  volumes = create_volumes(INSTANCES_NUMBER)
  reservation = create_instances(INSTANCES_NUMBER)
  wait_instances_started(reservation)
  attach_volumes(reservation, volumes)
  for instance in reservation.instances:
    print '\nID: {}\tIP: {}\t{}\tPlacement: {}'.format(instance.id,
                                                    instance.private_ip_address,
                                                    instance.ip_address,
                                                    instance.placement)

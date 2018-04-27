import boto
from boto.ec2.regioninfo import RegionInfo

region = RegionInfo(name='melbourne', endpoint='nova.rc.nectar.org.au')

ec2_conn = boto.connect_ec2(aws_access_key_id='a0b0a6f186c14f88bc9a250f8ca4f0a0',
                            aws_secret_access_key='2e87b1e89c704609808943e81c0b06b8',
                            is_secure=True,
                            region=region,
                            port=8773,
                            path='/services/Cloud',
                            validate_certs=False)

from pwntools import *
from randcrack import RandCrack

r = remote('challenge.nahamcon.com', port)
print(r.recvline())

# set seed
r.send(b'1\r\n')
print(r.recvline())

# get samples
rc = RandCrack()
for i in range(624):
    r.send(b'2\r\n')
    sample = r.recvline()
    rc.submit(sample)

# predict next number
prediction = rc.predict_getrandbits(32)

r.send(b'3\r\n')
print(r.recvline())
r.send('{prediction}\r\n'.encode('ascii'))
print(r.recvline())

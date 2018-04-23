import timeit
import time

start = timeit.default_timer()

#Your statements here
time.sleep(max(-5,0))

stop = timeit.default_timer()

print int(stop - start)
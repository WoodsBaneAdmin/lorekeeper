import random
'''
Clutch Sizes -- Due to magic, dragons magic are unstable so they have trouble producing young (1-2 only)
75% -- 1 baby
20% -- 2 baby
5% -- 3 baby
'''
def clutch_size():
    result = random.randint(1,100)

    if result <= 5:
        size = 3
    elif result > 5 and result <=26:
        size = 2
    else:
        size = 1
    
    return size

'''
Anomalies
'''
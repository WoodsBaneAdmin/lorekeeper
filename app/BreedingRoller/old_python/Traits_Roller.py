import random

'''
Drakovai Type Calculator
1% chance to be a random dragon of higher rarity
5% chance to be a random dragon of either parent’s rarity
47/47 given parents are same rarity 
'''

'''Drakovai_rarity = {"wyvern":"common","drake":"common","ampithere":"common",
                   "true dragon":"uncommon","wyrm":"uncommon",
                   "lung":"rare",
                   "leviathan":"legendary"}

rarity_score = {"common":1,"uncommon":2,"rare":3,"legendary":4}'''

Drakovai_rarity = {"wyvern":"common","drake":"common",
                   "true dragon":"uncommon"}

rarity_score = {"common":1,"uncommon":2}

#Drakovai_rarity = {"wyvern":"common"}
#rarity_score = {"common":1}

    
def highest_rarity():
    high = max(rarity_score.values())
    type = [key for key, value in rarity_score.items() if value == high]
    return type[0]
    
def calculate_type(type1,type2):
    rarity1 = Drakovai_rarity[type1]
    rarity2 = Drakovai_rarity[type2]
    
    result = random.randint(1,100)

    if rarity_score[rarity1] - rarity_score[rarity2] > 0:
        high = [rarity1,type1]
        low = [rarity2,type2]
    else:
        high = [rarity2,type2]
        low = [rarity1,type1]

    match result:
        case 1 if high[0] == highest_rarity():
            # Case 1: 1% chance to be a random dragon of higher rarity (but there is no higher rarity)
            types = {key: value for key, value in Drakovai_rarity.items() if value == high[0]}
            child_type = random.choice(list(types.keys()))
        case 1:
            # Case 1: 1% chance to be a random dragon of higher rarity
            rarities = {key: value for key, value in rarity_score.items() if value == rarity_score[high[0]]+1}
            types = {key: value for key, value in Drakovai_rarity.items() if value in list(rarities.keys())}
            child_type = random.choice(list(types.keys()))
        case 2 | 3 | 4 | 5 | 6:
            # Case 2: 5% chance to be a random dragon of either parent's rarity
            types = {key: value for key, value in Drakovai_rarity.items() if value == high[0] or value == low[0]}
            child_type = random.choice(list(types.keys()))
        case _:
            # Default: inherit one of the parent's types
            score = rarity_score[high[0]] - rarity_score[low[0]]

            match score:
                case 0: # 50% chance of inheriting parent 1
                    child_type = random.choice([low[1], high[1]])
                case 1: # 66% chance of inheriting less rare type 
                    child_type = random.choice([low[1],low[1],high[1]])
                case 2: # 75% chance of inheriting less rare type 
                    child_type = random.choice([low[1],low[1],low[1],high[1]])
                case _: # 83% chance of inheriting less rare type 
                    child_type = random.choice([low[1],low[1],low[1],low[1],low[1],high[1]])

    return child_type


'''
Crossover Traits: If the baby dragon is one of the two parents types, there is a chance it can get one of the parent’s traits
90% chance of nothing
5% of one trait
3% of two traits
2% of three traits
'''

Drakovai_traits = {"wyvern":{"body variant":["scaled","feathered"],"head":["crest","none"],"mouth":["basic","primitive","beak"]},
                   "drake":{"body variant":["scaled","armored"],"head":["frill","none"],"mouth":["basic","primitive","tusk"]},
                   "true dragon":{"body variant":["scaled","armored"],"placeholder":["temp"],"mouth":["basic","primitive","fangs"]}}

def calculate_variantTraits(parent1, parent2, child):

    child_traits = {}

    if child != parent1 and child != parent2:
        traits = list(Drakovai_traits[child].keys())
        for trait in traits:
            choice = random.choice(Drakovai_traits[child][trait])
            child_traits[trait] = choice

    elif child == parent1 and child == parent2:
        traits = list(Drakovai_traits[child].keys())
        for trait in traits:
            choice = random.choice(Drakovai_traits[child][trait])
            child_traits[trait] = choice

    else:
        result = random.randint(1,100)

        if child == parent1:
            different = parent2
        else:
            different = parent1

        match result:
            case 1 | 2:
                traits = list(Drakovai_traits[different].keys())

                if len(traits) == 3:
                    for trait in traits:
                        choice = random.choice(Drakovai_traits[different][trait])
                        child_traits[trait] = choice
                    
                    traits = list(Drakovai_traits[child].keys())
                    for trait in traits:
                        if trait not in list(child_traits.keys()):
                            choice = random.choice(Drakovai_traits[child][trait])
                            child_traits[trait] = choice
                
                else:
                    for i in range(3):
                        trait = random.choice(traits)
                        while trait in child_traits:
                            trait = random.choice(traits)

                        choice = random.choice(Drakovai_traits[different][trait])
                        child_traits[trait] = choice

                    traits = list(Drakovai_traits[child].keys())

                    for trait in traits:
                        if trait not in list(child_traits.keys()):
                            choice = random.choice(Drakovai_traits[child][trait])
                            child_traits[trait] = choice
            
            case 3 | 4 | 5:
                traits = list(Drakovai_traits[different])
                for i in range(2):
                    trait = random.choice(traits)
                    while trait in child_traits:
                        trait = random.choice(traits)

                    choice = random.choice(Drakovai_traits[different][trait])
                    child_traits[trait] = choice

                traits = list(Drakovai_traits[child].keys())

                for trait in traits:
                    if trait not in list(child_traits.keys()):
                        choice = random.choice(Drakovai_traits[child][trait])
                        child_traits[trait] = choice

            case 6 | 7 | 8 | 9 | 10:
                traits = list(Drakovai_traits[different].keys())
                trait = random.choice(traits)

                choice = random.choice(Drakovai_traits[different][trait])
                child_traits[trait] = choice

                traits = list(Drakovai_traits[child].keys())

                for trait in traits:
                    if trait not in list(child_traits.keys()):
                        choice = random.choice(Drakovai_traits[child][trait])
                        child_traits[trait] = choice

            case _:
                traits = list(Drakovai_traits[child].keys())

                for trait in traits:
                    choice = random.choice(Drakovai_traits[child][trait])
                    child_traits[trait] = choice

    child_traits = sorted(child_traits.items())
    return child_traits



'''
Horn Location + Range
ALL Dragons
Cape (Head+Spine)
Always small horn range
Other locations dragon type specific
Neck
Thorax
Legs (includes shoulder +hip)
Tail

Horn Ranges
None: 0
Small: 1-5
Medium: 6-10
Large: 11+
'''

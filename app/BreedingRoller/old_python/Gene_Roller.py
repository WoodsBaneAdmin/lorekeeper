import random

#base color
coats = {"Black": ["Bb Rr Yy" ,"BB Rr Yy", "Bb RR Yy" , "BB RR Yy" , "Bb Rr YY", "BB Rr YY" , "Bb RR YY" , "BB RR YY"],
"White": ["bb rr yy"], "Red": ["bb Rr yy","bb RR yy"], "Yellow": ["bb rr Yy", "bb rr YY"], "Blue": ["Bb rr yy", "BB rr yy"],
"Orange": ["bb Rr Yy", "bb Rr YY", "bb RR Yy", "bb RR YY"], "Green": ["Bb rr Yy" , "Bb rr YY" ,"BB rr Yy" ,"BB rr YY"],
"Purple": ["Bb Rr yy" , "Bb RR yy" , "BB Rr yy" , "BB RR yy"]}

#changes the shade of the basecoat
dilutions = {"Scorched": ["nSc" , "ScSc"], "Sunglow": ["nSg" , "SgSg"], "Dimmed": ["nDi" , "DiDi"]}

#go on top of bas color
markings = {"Faded":["nFa","FaFa"], "Speckled":["nSp","SpSp"], "Barred": ["nBr" , "BrBr"],"Piebald": ["nPi" , "PiPi"],
            "Mottled": ["nMo" , "MoMo"], "Striated":["nSt","StSt"],"Pointed": ["nP" , "PP"], "Cloaked": ["nCl" , "ClCl"],
            "Shimmer":"shsh", "Shimmer (carried)": "nsh", "Gradient": ["nGr" , "GrGr"], "Hooded":["nHo" , "HoHo"],
            "Marbled":["nMar" , "MarMar"],"Patternless": "plpl", "Patternless (carried)": "npl", "Albino": "aa", "Albino (carried)": "na",
            "Melanism": "mm", "Melanism (carried)": "nm", "Painted": ["nPa" , "PaPa"], 
            "Python": ["nPy" , "PyPy"], "Lightning": ["nLi" , "LiLi"],"Lightning Python": "LiPy",
            "Eyespots":["nESp","ESpESp"],"Rosette":["nRo,RoRo"],"Element Touched":["nEle","EleEle"]}

######
# Function:
# Description:
# Input:
# Output:
# Requirements: list of genes, assumes first 3 are the base coat in the correct order
######
def basecoat (genes):
    # puts the genes in the format that can be used to look at the base coats
    base = genes[0]+" "+genes[1]+" "+genes[2]
        # list out keys and values separately
    key_list = list(coats.keys())
    val_list = list(coats.values())

    position = 0
    for i in val_list:
        if base in i:
            #index = i.index(base)
            break
        else:
            position += 1
    #only 7 total basecoats so going beyond that meant they could not find the basecoat
    if position >= (len(key_list)):
        return "Invalid gene"
    else:
        value = key_list[position]
        return value

######
# Function:
# Description:
# Input:
# Output:
# Requirements:
######
def dilution (gene):
    key_list = list(dilutions.keys())
    val_list = list(dilutions.values())

    position = 0
    for i in val_list:
        if gene in i:
            index = i.index(gene)
            break
        else:
            position += 1
    if position >= (len(key_list)):
        return "Invalid gene"  
    else:
        value = key_list[position]
        return value

######
# Function:
# Description:
# Input:
# Output:
# Requirements:
######
def marking (gene):
    key_list = list(markings.keys())
    val_list = list(markings.values())

    position = 0
    index = 0
    for i in val_list:
        if gene in i:
            index = i.index(gene)
            break
        else:
            position += 1
    if position >= (len(key_list)):
        return "Invalid gene"  
    else:
        value = key_list[position]
        return value

######
# Function: phenotype
# Description: From the set of rolled genes, identify the phenotype
# Input: list of genes
# Output: string saying the phenotypes
# Requirements: list of genes, assumes first 3 are the base coat
######
def phenotype (genes):
    #first finds the basecoat of the genes
    base = basecoat(genes)
    dil = []
    mark = []
    #determining if there is more than just the basecoat genes
    if len(genes) > 3:
        #looks at each gene in genes
        for gene in genes:
            #determining if it is a dilution gene
            if dilution(gene) != "Invalid gene":
                dil.append(dilution(gene))
            #determining if it is a marking gene
            if marking(gene) != "Invalid gene":
                mark.append(marking(gene))
    # checking to make sure no invalid genes were inputted
    if base == "Invalid gene" or dil == "Invalid gene" or mark == "Invalid gene":
        return "Invalid gene"
    #returns the full phenotype in correct order
    else:
        return ' '.join(dil)+" "+base+" "+' '.join(mark)

######
# Function: gene_roller
# Description: Takes two sets of genes, isolates randome alleles from each, combines them, outputting a phenotype and genotype
# Input: two lists of genes
# Output: two strings, one phenotype and one genotype
# Requirements: two list of genes, following functions: allele_roller, combine_alleles, and phenotype
######
def gene_roller (genes_1, genes_2):
    #chooses the set of alleles that will be passed from the first geneset
    alleles_1 = allele_roller(genes_1)

    #chooses the set of alleles that will be passed from the second geneset
    alleles_2 = allele_roller(genes_2)

    #takes both set of alleles and combines them together to figure out the genes
    genes = combine_alleles(alleles_1, alleles_2)

    #returns the phenotype and genotype of the offspring
    return phenotype(genes), ' '.join(genes)

######
# Function: allele_roler(list)
# Description: Takes a set of genes and randomly chooses which alleles to output
# Input: list of genes
# Output: list of alleles
# Requirements: list of genes, the first allele will always be less or equal in length than second
######
def allele_roller (genes):
    #lists of chosen alleles
    allele_list = []

    #looks at the list of genes
    for gene in genes:
        #splits up genes into it's two alleles, assumes that in case of odd length, the first allele is less in length than second
        if gene[0] != "n":
            alleles = [gene[:len(gene)//2],gene[len(gene)//2:]]
        else:
            alleles = [gene[0],gene[1:]]
        #randomly chooses one of two alleles
        allele = random.choice(alleles)
        #checks that chosen allele was not null
        if allele != "n":
            #appends allele to allele list
            allele_list.append(allele)
    #returns the allele_list
    return allele_list

######
# Function: combine_alleles
# Description: combines two sets of alleles into a geneset
# Input: two lists of alleles
# Output: one list of genes
# Requirements: set of two alleles, 3 basecoat alleles must be in front for each set
######
def combine_alleles(alleles_1, alleles_2):
    #defining the basecoat alleles to make sure to isolate those
    basecoat_alleles = ["B","R","Y"]
    basecoat_lower_alleles = ["b","r","y"]
    genes = []

    #combining the alleles together based on alleles 1
    for allele in alleles_1:
        #checking to see if allele is one of the basecoat allels and has the dom basecoat allele
        if allele in basecoat_alleles:
            #checking to see if both dominant or not
            if allele in alleles_2:
                genes.append(allele+alleles_2[alleles_2.index(allele)])
            else:
                genes.append(allele+allele.lower())
        #checking to see if allele 1 has the rec basecoate allele
        elif allele in basecoat_lower_alleles:
            #checking to see if allele 2 is also rec for that basecoat
            if allele in alleles_2:
                genes.append(allele+alleles_2[alleles_2.index(allele)])
            else:
                genes.append(allele.upper()+allele)
        #special case looking to see if LiPy is in play
        elif allele == "Py":
            if "Py" in alleles_2:
                genes.append("PyPy")
            elif "Li" in alleles_2:
                genes.append("LiPy")
            else:
                genes.append("nPy")
        elif allele == "Li":
            if "Py" in alleles_2:
                genes.append("LiPy")
            elif "Li" in alleles_2:
                genes.append("LiLi")
            else:
                genes.append("nLi")
        #the rest can be appended as necessary
        elif allele in alleles_2 and allele != "Py" and allele != "Li":
            genes.append(allele+alleles_2[alleles_2.index(allele)])
        else:
            genes.append("n"+allele)
    #double checking there were no missed cases in alleles 2 (ie heterozygous)
    for allele in alleles_2:
        if allele in alleles_1 or allele in basecoat_alleles or allele in basecoat_lower_alleles:
            continue
        elif "n"+allele in genes or "LiPy" in genes:
            continue
        else:
            genes.append("n"+allele)
    # returns the now combined list of genes
    return genes

######
# Function:
# Description:
# Input:
# Output:
# Requirements:
######
def main():
    # gets first genome
    genome = input("Enter the genome of the first dragon: ")
    #splits it up based on spaces
    first_genes = genome.split(" ")

    #gets second genome
    genome = input("Enter the genome of the seconds dragon: ")
    #splits it up based on spaces
    second_genes = genome.split(" ")

    #outputs 4 new genomes based on a combination of the first two
    for i in range(4):
        results=gene_roller(first_genes,second_genes)
        print("Offspring Number "+str(i+1)+"\nPhenotype: "+results[0]+"\nGenotype: "+results[1])

def check_genome(coats, genome):
  for coat in coats:
    if genome[:8] in coat:
      return True
  return False
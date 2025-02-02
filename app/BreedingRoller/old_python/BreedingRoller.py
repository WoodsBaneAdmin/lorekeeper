from flask import Flask, request, jsonify
import Gene_Roller
import Traits_Roller 
import Misc_Roller

app = Flask(__name__)

@app.route('/roll', methods=['POST'])
def roll():
    try:
        data = request.get_json()
        genome1 = data.form['geno1']
        geno1 = genome1.split(" ")
        genome2 = data.form['geno2']
        geno2 = genome2.split(" ")

        type1 = data.form['type1']
        type2 = data.form['type2']

        if Gene_Roller.check_genome(list(Gene_Roller.coats.values()),genome1) and Gene_Roller.check_genome(list(Gene_Roller.coats.values()),genome2):
            clutch = Misc_Roller.clutch_size()
            output = []

            for i in range(clutch):
                color = Gene_Roller.gene_roller(geno1,geno2)

                child_type = Traits_Roller.calculate_type(type1,type2)
                child_traits = Traits_Roller.calculate_variantTraits(type1,type2,child_type)

                result = [str(i+1),color[0],color[1],child_type.title(),child_traits]

                output.append(result)
            return jsonify({'outputs': output, 'clutch': clutch})
        else:
            return jsonify({'error': 'Invalid genomes'}), 400

    except Exception as e:  # Handle potential errors
        return jsonify({'error': str(e)}), 500  # Return error code


if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0')
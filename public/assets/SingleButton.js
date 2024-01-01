import {StyleSheet, Text, View, Image} from 'react-native';
import React from 'react';

const SingleButton = ({Ws, ST, UD, BG, category}) => {
  // const Ws = 1;
  // const ST = 1;
  // const UD = 1;
  // const BG = 0;
  // const category = 'Ventilation';

  let bgColor;
  if (BG === 0) {
    bgColor = 'black';
  } else if (BG === 1) {
    bgColor = 'green';
  } else if (BG === 2) {
    bgColor = 'red';
  }

  return (
    <View style={styles.mainContainer}>
      <View style={styles.first}>
        <Text style={styles.firstText}>FKI</Text>
        <Image
          source={require('../assets/warning-icon-red.png')}
          style={styles.firstIcon}
        />
      </View>

      <View style={[styles.second, {backgroundColor: bgColor}]}>
        <Text style={styles.secondText}>{category}</Text>
        <View style={styles.secondIconContainer}>
          <Image
            source={Ws == 1 ? require('../assets/warning-icon-red.png') : null}
            style={styles.secondIcon}
          />
          <Image
            source={
              ST == 1
                ? require('../assets/star-icon-white_filled.png')
                : require('../assets/star-icon-white.png')
            }
            style={styles.secondIcon}
          />
          <Image
            source={
              UD == 1
                ? require('../assets/arrow-circle-up-icon-White.png')
                : require('../assets/arrow-circle-up-icon-White.png')
            }
            style={styles.secondIcon}
          />
        </View>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  mainContainer: {
    justifyContent: 'space-evenly',
    alignItems: 'center',
  },

  first: {
    paddingHorizontal: 20,
    backgroundColor: 'skyblue',
    flexDirection: 'row',
    alignItems: 'center',
    width: '20%',
    marginBottom: 30,
    marginTop: 50,
  },

  firstIcon: {
    width: 40,
    height: 40,
  },

  firstText: {
    textAlign: 'center',
    fontSize: 24,
    color: 'red',
    fontWeight: '400',
  },

  second: {
    borderRadius: 12,
    height: 50,
    width: 300,
    alignItems: 'center',
    flexDirection: 'row',
    paddingHorizontal: 12,
    justifyContent: 'space-between',
  },

  secondText: {
    color: 'white',
    textDecorationLine: 'underline',
    fontSize: 18,
  },

  secondIcon: {
    height: 40,
    width: 40,
    marginRight: 8,
  },

  secondIconContainer: {
    flexDirection: 'row',
    justifyContent: 'space-evenly',
  },
});

export default SingleButton;


// Circle down icon is pending
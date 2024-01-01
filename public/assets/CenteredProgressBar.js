import React from 'react';
import {View, Text, StyleSheet, Image} from 'react-native';

const CenteredProgressBar = ({IST, RIV, BG}) => {
  const normalizedPercentage = Math.min(Math.max(100 - RIV, 0), 100);

  let bgColor;
  if (BG === 0) {
    bgColor = 'orange';
  } else if (BG === 1) {
    bgColor = '#4CAF50';
  } else if (BG === 2) {
    bgColor = 'red';
  }

  const leftFill = Math.min(normalizedPercentage / 2, 50);
  const rightFill = Math.min(normalizedPercentage / 2, 50);

  return (
    <View style={{width: '60%', alignItems: 'center'}}>
      <View style={styles.percentageContainer}>
        <Text style={styles.text}>{RIV}%</Text>
      </View>
      <View style={{flexDirection: 'row', alignItems: 'center'}}>
        <View>
          <Image
            source={require('../assets/house_in.png')}
            style={styles.images}
          />
        </View>
        <View style={[styles.progressBarContainer, {backgroundColor: bgColor}]}>
          <View style={{...styles.fill, width: `${leftFill}%`}} />
          <View style={{flex: 50}} />
          <View style={{...styles.fill, width: `${rightFill}%`}} />
        </View>
        <View>
          <Image
            source={require('../assets/house_out.png')}
            style={styles.images}
          />
        </View>
      </View>
      <View>
        <Text>{IST}</Text>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  percentageContainer: {
    borderWidth: 1,
    borderColor: 'black',
    justifyContent: 'center',
    marginTop: 20,
    paddingHorizontal: 8,
    paddingVertical: 2,
    marginBottom: 4,
  },
  progressBarContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    height: 20,
    borderRadius: 4,
    overflow: 'hidden',
    width: '100%',
    borderWidth: 1,
    borderColor: 'black',
  },
  fill: {
    backgroundColor: '#fffffe',
    height: '100%',
  },
  text: {
    textAlign: 'center',
  },

  images: {
    width: 30,
    height: 30
  }
});

export default CenteredProgressBar;

// {
//   // `${100 - normalizedPercentage}%`
// }

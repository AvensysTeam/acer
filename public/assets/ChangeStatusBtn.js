import {StyleSheet, Text, View} from 'react-native';
import React from 'react';

const ChangeStatusBtn = ({CST, BG}) => {
  let bgColor;
  if (BG === 0) {
    bgColor = '#4CAF50';
  } else if (BG === 1) {
    bgColor = 'white';
  } else if (BG === 2) {
    bgColor = 'red';
  }

  return (
    <View style={[styles.container, {backgroundColor: bgColor}]}>
      <Text style={styles.text}>{CST}</Text>
    </View>
  );
};

export default ChangeStatusBtn;

const styles = StyleSheet.create({
  container: {
    borderWidth: 1,
    borderColor: 'black',
    padding: 8,
    borderRadius: 6,
  },

  text: {
    color: 'black'
  },
});

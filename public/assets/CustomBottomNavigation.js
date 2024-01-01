import React from 'react';
import {View, StyleSheet, Dimensions} from 'react-native';
import SvgUri from 'react-native-svg-uri';

const {width, height} = Dimensions.get('window');

const CustomBottomNavigation = ({HI, PI, II, SI, OC}) => {
  let OCColor;
  if (OC === 0) {
    OCColor = 'black';
  } else if (OC === 1) {
    OCColor = 'red';
  }

  return (
    <View style={[styles.container, {
        borderColor: OCColor,}]}>
      <SvgUri width="50" height="50" source={HI} />

      <SvgUri width="50" height="50" source={PI} />

      <SvgUri width="50" height="50" source={II} />

      <SvgUri width="50" height="50" source={SI} />
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    width: width * 0.9,
    flexDirection: 'row',
    justifyContent: 'space-around',
    borderWidth: 1,
    borderRadius: 12,
    marginBottom: 12
  },
});

export default CustomBottomNavigation;


import {StyleSheet, Text, View, TouchableOpacity} from 'react-native';
import React from 'react';

const SearchingButton = ({SBT, BG}) => {
  let bgColor;
  if (BG == 0) {
    bgColor = 'black';
  } else if (BG == 1) {
    bgColor = 'orange';
  } else if (BG == 2) {
    bgColor = 'red';
  }

  return (
    <View>
      <TouchableOpacity style={[styles.container, {backgroundColor: bgColor}]}>
        <Text style={styles.containerText}>{SBT}</Text>
      </TouchableOpacity>
    </View>
  );
};

export default SearchingButton;

const styles = StyleSheet.create({
  container: {
    paddingVertical: 12,
    paddingHorizontal: 30,
    borderWidth: 1,
    borderColor: 'black',
    borderRadius: 8,
    marginTop: 8
  },

  containerText: {
    color: 'white',
    fontSize: 18,
  },
});

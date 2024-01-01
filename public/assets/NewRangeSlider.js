import React, {useState} from 'react';
import {View, StyleSheet, Text} from 'react-native';
import MultiSlider from '@ptomasroos/react-native-multi-slider';

const NewRangeSlider = ({TPR, VL1, VL2, BG}) => {
  const [values, setValues] = useState([VL1, VL2]);

  const handleValuesChange = values => {
    setValues(values);
  };

  const renderCustomMarker = (index, selected, value) => {
    const position = index === 0 ? -20 : 20;
    const valueStyle = {
      ...styles.markerText,
      color: selected ? 'white' : 'black',
      top: position,
    };

    return (
      <View
        style={{
          ...styles.customMarker,
          backgroundColor: selected ? 'green' : 'lightgrey',
        }}>
        <Text style={valueStyle}>{value}</Text>
      </View>
    );
  };

  return (
    <View style={styles.container}>
      <Text style={{fontSize: 18, color: 'black', marginBottom: 8}}>{TPR}</Text>
      <Text style={styles.values}>{values[0]}</Text>
      <MultiSlider
        values={values}
        min={0}
        max={100}
        onValuesChange={handleValuesChange}
        sliderLength={300}
        selectedStyle={styles.selectedStyle}
        unselectedStyle={styles.unselectedStyle}
        containerStyle={styles.containerStyle}
        trackStyle={styles.trackStyle}
        customMarker={renderCustomMarker}
      />
      <Text style={styles.values}>{values[1]}</Text>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    alignItems: 'center',
    paddingTop: 20,
    borderWidth: 1,
    borderColor: 'black',
    padding: 12,
    borderRadius: 12,
    backgroundColor: 'white',
  },

  values: {
    color: 'black',
  },

  selectedStyle: {
    backgroundColor: 'green',
    height: 10,
    marginTop: -5,
    borderRadius: 10,
    borderWidth: 1,
    borderColor: 'black',
  },

  unselectedStyle: {
    backgroundColor: 'lightgrey',
    height: 10,
    marginTop: -5,
    borderRadius: 10,
    borderWidth: 1,
    borderColor: 'black',
  },

  containerStyle: {
    height: 40,
    borderRadius: 10,
  },

  trackStyle: {
    height: 50,
  },

  customMarker: {
    width: 20,
    height: 20,
    borderRadius: 10,
    borderWidth: 1,
    borderColor: 'black',
    justifyContent: 'center',
    alignItems: 'center',
  },

  markerText: {
    color: 'black',
    fontWeight: 'bold',
    position: 'absolute',
  },
});

export default NewRangeSlider;

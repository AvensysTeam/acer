import React, {useState} from 'react';
import {View, TouchableOpacity, StyleSheet, Text} from 'react-native';

const ToggleSwitch = ({TOO, CL, CR, BG}) => {
  const [isWifi, setIsWifi] = useState(true);
  // const TOO = 'Communication';
  // const CL = 'wifi';
  // const CR = 'BT';

  const handleToggle = () => {
    setIsWifi(!isWifi);
  };

  return (
    <View
      style={{
        justifyContent: 'center',
        alignItems: 'center',
        width: '100%',
        height: '100%',
      }}>
      <Text style={{textAlign: 'center', marginBottom: 8}}>{TOO}</Text>
      <TouchableOpacity style={styles.container} onPress={handleToggle}>
        <View
          style={[
            styles.circle,
            isWifi ? styles.leftCircle : styles.rightCircle,
            BG == 0 ? {backgroundColor: '#4CAF50'} : BG == 1 ? {backgroundColor: 'orange'} : {backgroundColor: 'red'}
          ]}
        />
      </TouchableOpacity>
      <View
        style={{
          justifyContent: 'space-between',
          flexDirection: 'row',
          width: 150
        }}>
        <Text>{CL}</Text>
        <Text>{CR}</Text>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    width: 150,
    height: 20,
    borderRadius: 10,
    backgroundColor: 'white',
    borderColor: 'black',
    borderWidth: 1,
    flexDirection: 'row',
    overflow: 'hidden',
  },
  circle: {
    width: 20,
    height: 20,
    borderRadius: 10,
    backgroundColor: '#4CAF50',
    borderWidth: 1,
    borderColor: 'black',
    position: 'absolute',
    top: -1,
  },
  leftCircle: {
    left: -1,
  },
  rightCircle: {
    right: -1,
  },
});

export default ToggleSwitch;

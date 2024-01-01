import {Pressable, StyleSheet, Text, View} from 'react-native';
import React, {useState, useEffect} from 'react';

const TrippleBtn = ({TBL, TBC, TBR}) => {
  const [firstContainer, setFirstContainer] = useState('white');
  const [secondContainer, setSecondContainer] = useState('white');
  const [thirdContainer, setThirdContainer] = useState('white');

  useEffect(() => {
    if (TBL === 1) {
      setFirstContainer('lightgreen');
      setSecondContainer('white');
      setThirdContainer('white');
    } else if (TBC === 1) {
      setFirstContainer('white');
      setSecondContainer('lightgreen');
      setThirdContainer('white');
    } else if (TBR === 1) {
      setFirstContainer('white');
      setSecondContainer('white');
      setThirdContainer('lightgreen');
    }
  }, [TBL, TBC, TBR]);

  return (
    <View
      style={{
        justifyContent: 'center',
        alignItems: 'center',
        width: '100%',
        height: '100%',
        flexDirection: 'row',
      }}>
      <Pressable
        onPress={() => (
          setFirstContainer('lightgreen'),
          setSecondContainer('white'),
          setThirdContainer('white')
        )}>
        <View
          style={{
            backgroundColor: `${firstContainer}`,
            padding: 30,
            marginRight: 8,
            borderWidth: 1,
            borderColor: '#000000',
            borderRadius: 12,
          }}>
          <Text>FSC</Text>
        </View>
      </Pressable>
      <Pressable
        onPress={() => (
          setFirstContainer('white'),
          setSecondContainer('lightgreen'),
          setThirdContainer('white')
        )}>
        <View
          style={{
            backgroundColor: `${secondContainer}`,
            padding: 30,
            marginRight: 8,
            borderWidth: 1,
            borderColor: '#000000',
            borderRadius: 12,
          }}>
          <Text>CAP</Text>
        </View>
      </Pressable>
      <Pressable
        onPress={() => (
          setFirstContainer('white'),
          setSecondContainer('white'),
          setThirdContainer('lightgreen')
        )}>
        <View
          style={{
            backgroundColor: `${thirdContainer}`,
            padding: 30,
            marginRight: 8,
            borderWidth: 1,
            borderColor: '#000000',
            borderRadius: 12,
          }}>
          <Text>CAF</Text>
        </View>
      </Pressable>
    </View>
  );
};

const styles = StyleSheet.create({});
export default TrippleBtn;

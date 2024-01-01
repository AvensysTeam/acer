import React, { useState, useRef, useEffect } from 'react';
import { View, TouchableOpacity, Text, PanResponder, StyleSheet, Image } from 'react-native';
import Svg, { Rect } from 'react-native-svg';

const VerticalProgressBar = ({ FSI, VS, TS, BG }) => {
  const [progress, setProgress] = useState(VS/100);

  const panResponder = useRef(
    PanResponder.create({
      onStartShouldSetPanResponder: () => true,
      onPanResponderMove: (_, gestureState) => {
        const newProgress = Math.max(0, Math.min(1, 1 - gestureState.moveY / 150));
        setProgress(newProgress);
      },
    }),
  ).current;

  const increaseProgress = () => {
    setProgress(prevProgress => Math.min(prevProgress + 0.1, 1.0));
  };

  const decreaseProgress = () => {
    setProgress(prevProgress => Math.max(prevProgress - 0.1, 0.0));
  };

  const barWidth = 24;

  const progressBarHeight = 150 * progress;
  const percentage = Math.round(progress * 100);

  return (
    <View style={styles.container}>
      <Text>{TS}</Text>
      <TouchableOpacity onPress={increaseProgress} style={styles.button}>
        <Image source={require('../assets/fan.png')} style={{ height: 40, width: 40 }} />
      </TouchableOpacity>
      <View style={styles.progressBarContainer} {...panResponder.panHandlers}>
        <Svg width={barWidth} height={150}>
          <Rect
            x={0}
            y={150 - progressBarHeight}
            width={barWidth}
            height={progressBarHeight}
            fill={
              percentage < 33
                ? 'black'
                : percentage > 33 && percentage < 67
                ? 'orange'
                : 'red'
            }
          />
        </Svg>
      </View>
      <TouchableOpacity onPress={decreaseProgress} style={styles.button}>
        <Image source={require('../assets/fan.png')} style={{ height: 30, width: 30 }} />
      </TouchableOpacity>
      <Text style={styles.percentageText}>{percentage}%</Text>
      <View style={styles.buttonContainer}></View>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    width: 78,
    flexDirection: 'column',
    alignItems: 'center',
    borderRadius: 12,
    borderWidth: 1,
    borderColor: 'black',
    padding: 10,
    margin: 10,
    justifyContent: 'center',
  },
  buttonContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    width: '100%',
    paddingHorizontal: 50,
  },
  button: {
    padding: 10,
    borderRadius: 5,
    color: 'black',
  },
  buttonText: {
    fontSize: 30,
    color: 'white',
    color: 'black',
  },
  progressBarContainer: {
    borderWidth: 2,
    borderColor: 'black',
    borderRadius: 8,
    overflow: 'hidden',
    alignItems: 'center',
    width: 24,
    height: 150,
  },
  percentageText: {
    fontSize: 18,
    marginTop: 10,
  },
});

export default VerticalProgressBar;

import React, { useState, useEffect } from 'react';
import { View, Text, PanResponder, StyleSheet } from 'react-native';

const CountdownProgressBar = ({ TSB, TSL, TSR, RIV, BG }) => {
  const progressBarWidth = 300;
  const circleSize = 19;
  const progressBarHeight = 21;

  let bgColor;
  if (BG === 0) {
    bgColor = 'orange';
  } else if (BG === 1) {
    bgColor = '#4CAF50';
  } else if (BG === 2) {
    bgColor = 'red';
  }

  const [progress, setProgress] = useState(RIV);
  const [circlePosition, setCirclePosition] = useState({
    left: RIV * progressBarWidth - circleSize / 2,
    bottom: (progressBarHeight - circleSize) / 2,
  });

  useEffect(() => {
    const newCirclePosition = {
      left: RIV * progressBarWidth - circleSize / 2,
      bottom: (progressBarHeight - circleSize) / 2,
    };
    setCirclePosition(newCirclePosition);
  }, [RIV, progressBarWidth, circleSize, progressBarHeight]);

  const handlePanResponderMove = (_, gestureState) => {
    const touchX = Math.min(progressBarWidth, Math.max(0, gestureState.moveX));
    const newProgress = touchX / progressBarWidth;
    const maxCircleLeft = progressBarWidth - circleSize;

    const newCirclePosition = {
      left: Math.min(maxCircleLeft, touchX - circleSize / 2),
      bottom: (progressBarHeight - circleSize) / 2,
    };

    setProgress(newProgress);
    setCirclePosition(newCirclePosition);
  };

  const panResponder = PanResponder.create({
    onStartShouldSetPanResponder: () => true,
    onMoveShouldSetPanResponder: () => true,
    onPanResponderMove: handlePanResponderMove,
  });

  const filledWidth = progressBarWidth * progress;

  return (
    <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center' }}>
      <Text style={{ marginBottom: 12 }}>{TSB}</Text>
      <View {...panResponder.panHandlers}>
        <View
          style={{
            width: progressBarWidth,
            height: progressBarHeight,
            borderRadius: 18,
            borderColor: 'black',
            borderWidth: 1,
            backgroundColor: '#fff',
            position: 'relative',
          }}>
          <View
            style={{
              backgroundColor: bgColor,
              width: circleSize,
              height: circleSize,
              borderRadius: circleSize / 2,
              position: 'absolute',
              left: circlePosition.left,
              bottom: circlePosition.bottom,
              borderWidth: 1,
              borderColor: 'black',
            }}
          />
        </View>
        <View style={{ flexDirection: 'row', justifyContent: 'space-between' }}>
          <Text>{TSL}</Text>
          <Text>{TSR}</Text>
          <Text style={{ position: 'absolute', left: filledWidth, bottom: -10 }}>
            {`${Math.round(progress * (TSR - TSL) + TSL)}`}
          </Text>
        </View>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({});

export default CountdownProgressBar;

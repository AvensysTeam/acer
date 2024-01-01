import React, {useState, useEffect} from 'react';
import {View, StyleSheet, Text} from 'react-native';
import Svg, {Path} from 'react-native-svg';

const ActivationButton = ({TAB, rot}) => {
  const [rotationSpeed, setRotationSpeed] = useState(rot);
  const [rotationAngle, setRotationAngle] = useState(0);

  useEffect(() => {
    const intervalId = setInterval(() => {
      setRotationAngle(prevAngle => (prevAngle + rotationSpeed) % 360);
    }, 16);

    return () => {
      clearInterval(intervalId);
    };
  }, [rotationSpeed]);

  const housePath = `
    M218.25781,84.88623a100.15636,100.15636,0,1,1-22.436-30.36475l25.3501-25.34961a3.99957,3.99957,0,1,1,5.65624,5.65625L164.77051,96.88574l-.001.00147-.00146.001-33.93995,33.93994a3.99957,3.99957,0,1,1-5.65624-5.65625l30.97851-30.97851a43.98785,43.98785,0,1,0,15.77832,31.29346,3.99962,3.99962,0,1,1,7.98633-.45411,52.03173,52.03173,0,1,1-18.09131-36.51318l28.333-28.333a91.88714,91.88714,0,1,0,20.88232,28.14795,4,4,0,0,1,7.21875-3.44824Z
  `;

  return (
    <View style={styles.container}>
      <Text style={styles.title}>{TAB}</Text>
      <Svg width={100} height={100} viewBox="0 0 256 256">
        <Path
          d={housePath}
          fill="black"
          transform={`rotate(${rotationAngle}, 128, 128)`}
        />
      </Svg>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    justifyContent: 'center',
    alignItems: 'center',
  },

  title: {
    color: 'black',
    fontSize: 18,
  },
});

export default ActivationButton;

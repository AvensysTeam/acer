import React, { useRef } from 'react';
import { View, TouchableOpacity, Text, StyleSheet } from 'react-native';
import QRCode from 'react-native-qrcode-svg';
import Share from 'react-native-share';
import ViewShot from 'react-native-view-shot';

const QRCodeGeneratorWithSharing = ({ inputString }) => {
  const qrCodeRef = useRef(null);

  const handleShareQRCode = async () => {
    if (qrCodeRef.current) {
      try {
        const uri = await captureQRCode();

        const shareOptions = {
          url: uri,
          title: 'Share QR Code',
          type: 'image/png',
        };

        Share.open(shareOptions)
          .then(res => console.log(res))
          .catch(err => console.log(err));
      } catch (error) {
        console.error('Error capturing QR code:', error);
      }
    }
  };

  const captureQRCode = async () => {
    if (qrCodeRef.current) {
      const uri = await qrCodeRef.current.capture();
      return uri;
    }
  };

  return (
    <View
      style={{
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
      }}>
      <View style={{ borderWidth: 1, borderColor: 'white' }}>
        <ViewShot
          ref={qrCodeRef}
          options={{ format: 'png', quality: 1.0 }}
          style={{ paddingVertical: 4, backgroundColor: 'white' }}>
          <QRCode
            value={inputString}
            size={200}
            color="black"
            backgroundColor="white"
          />
        </ViewShot>
        <TouchableOpacity
          onPress={handleShareQRCode}
          style={[styles.btnContainer, { width: 200 }]}>
          <Text style={styles.btnText}>Click to share</Text>
        </TouchableOpacity>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  btnContainer: {
    backgroundColor: '#4CAF50',
    marginTop: 4
  },
  btnText: {
    color: 'white',
    fontSize: 18,
    textAlign: 'center',
    paddingVertical: 10,
    
  },
});

export default QRCodeGeneratorWithSharing;

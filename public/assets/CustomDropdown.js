import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  Image,
  FlatList,
  Dimensions,
} from 'react-native';
import React from 'react';
import {useState} from 'react';

const datadropdown = [
  {data: 'DE', index: '1'},
  {data: 'FR', index: '2'},
  {data: 'IT', index: '3'},
  {data: 'NL', index: '4'},
  {data: 'DA', index: '5'},
  {data: 'SV', index: '6'},
];

const {width, height} = Dimensions.get('window');

const CustomDropdown = ({VL, BG}) => {
  let bgColor;
  if (BG === 0) {
    bgColor = 'black';
  } else if (BG === 1) {
    bgColor = '#4CAF50';
  } else if (BG === 2) {
    bgColor = 'red';
  }

  const [selectedData, setSelectedData] = useState(VL);
  const [isClicked, setIsClicked] = useState(false);
  const [data, setData] = useState(datadropdown);
  const onSearch = txt => {
    if (txt !== '') {
      let tempData = data.filter(item => {
        return item.data.toLowerCase().indexOf(txt.toLowerCase()) - 1;
      });
      setData(tempData);
    } else {
      setData(data);
    }
  };
  return (
    <View style={styles.container}>
      <Text style={styles.heading}>Country Drop Down</Text>
      <TouchableOpacity
        style={[styles.dropdownSelector, {backgroundColor: bgColor}]}
        onPress={() => {
          setIsClicked(!isClicked);
        }}>
        <Text style={{color: 'white'}}>{selectedData}</Text>
        {isClicked ? (
          <Image
            source={require('../assets/upload.png')}
            style={styles.icon}></Image>
        ) : (
          <Image
            source={require('../assets/dropdown.png')}
            style={styles.icon}></Image>
        )}
      </TouchableOpacity>
      {isClicked ? (
        <View style={styles.dropdownArea}>
          <FlatList
            data={data}
            renderItem={({item, index}) => {
              return (
                <TouchableOpacity
                  style={styles.dataItem}
                  onPress={() => {
                    setSelectedData(item.data);
                    setIsClicked(false);
                  }}>
                  <Text>{item.data}</Text>
                </TouchableOpacity>
              );
            }}></FlatList>
        </View>
      ) : null}
    </View>
  );
};

export default CustomDropdown;
const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  heading: {
    fontSize: 20,
    fontWeight: '800',
    marginTop: 100,
    alignSelf: 'center',
  },
  dropdownSelector: {
    width: width * 0.4,
    height: 50,
    borderRadius: 10,
    borderWidth: 0.5,
    borderColor: 'black',

    alignSelf: 'center',
    marginTop: 50,
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingLeft: 15,
    paddingRight: 15,
  },
  icon: {
    width: 20,
    height: 20,
  },
  dropdownArea: {
    width: width * 0.2,
    height: 300,
    borderRadius: 10,
    marginTop: 10,
    marginRight: 25,
    backgroundColor: '#fff',
    elevation: 5,
    alignSelf: 'flex-end',
  },
  searchInput: {
    width: '90%',
    height: 50,
    borderRadius: 10,
    borderWidth: 0.5,
    borderColor: '#8e8e8e',
    alignSelf: 'center',
    marginTop: 20,
    paddingLeft: 15,
  },
  dataItem: {
    width: '85%',
    height: 50,
    borderBottomWidth: 0.2,
    borderBottomColor: '#8e8e8e',
    alignSelf: 'center',
    justifyContent: 'center',
  },
});

import React, {useState, useEffect} from 'react';
import {View, StyleSheet, Text} from 'react-native';
import {Picker} from '@react-native-picker/picker';

const DateSelector = ({DT, BG}) => {
  const [selectedDate, setSelectedDate] = useState('1');
  const [selectedMonth, setSelectedMonth] = useState('1');
  const [selectedYear, setSelectedYear] = useState('2023');
  const [days, setDays] = useState([]);

  let bgColor;
  if (BG === 0) {
    bgColor = 'orange';
  } else if (BG === 1) {
    bgColor = '#4CAF50';
  } else if (BG === 2) {
    bgColor = 'red';
  }

  const daysInMonth = (month, year) => {
    return new Date(year, month, 0).getDate();
  };

  const updateDays = () => {
    const daysInSelectedMonth = daysInMonth(
      parseInt(selectedMonth),
      parseInt(selectedYear),
    );
    const daysArray = [...Array(daysInSelectedMonth).keys()].map(day =>
      String(day + 1),
    );
    setDays(daysArray);

    if (parseInt(selectedDate) > daysInSelectedMonth) {
      setSelectedDate(String(daysInSelectedMonth));
    }
  };

  useEffect(() => {
    updateDays();
  }, [selectedMonth, selectedYear]);

  const onMonthChange = itemValue => {
    setSelectedMonth(itemValue);
  };

  const onYearChange = itemValue => {
    setSelectedYear(itemValue);
  };

  return (
    <View style={[styles.container, {backgroundColor: bgColor}]}>
      <View>
        <Text style={{color: 'white'}}>{DT}</Text>
      </View>
      <View style={styles.pickerContainer}>
        <Picker
          style={styles.picker}
          selectedValue={selectedDate}
          onValueChange={itemValue => setSelectedDate(itemValue)}>
          {days.map((day, index) => (
            <Picker.Item label={day} value={day} key={index} />
          ))}
        </Picker>
      </View>

      <View style={styles.pickerContainer}>
        <Picker
          style={styles.picker}
          selectedValue={selectedMonth}
          onValueChange={onMonthChange}>
          {[...Array(12).keys()].map(month => (
            <Picker.Item
              label={String(month + 1)}
              value={String(month + 1)}
              key={month}
            />
          ))}
        </Picker>
      </View>

      <View style={styles.lastPickerContainer}>
        <Picker
          style={styles.lastPicker}
          selectedValue={selectedYear}
          onValueChange={onYearChange}>
          {[2023, 2024, 2025, 2026, 2027].map(year => (
            <Picker.Item
              label={year.toString()}
              value={year.toString()}
              key={year}
            />
          ))}
        </Picker>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-evenly',
    padding: 10,
    width: '90%',
    borderRadius: 4,
  },
  pickerContainer: {
    borderColor: 'white',
    borderWidth: 1,
    borderRadius: 5,
    overflow: 'hidden',
  },
  picker: {
    width: 100,
    color: 'white',
    marginRight: 1,
  },
  lastPickerContainer: {
    borderColor: 'white',
    borderWidth: 1,
    borderRadius: 5,
    overflow: 'hidden',
  },
  lastPicker: {
    width: 120,
    color: 'white',
  },
});

export default DateSelector;

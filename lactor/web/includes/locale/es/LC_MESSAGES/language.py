#!/usr/bin/env python

import re
import json
import csv

class Translation:
  def __init__(self, language=None):
    """
    Creates a new Translation object

    :Parameters:
      language : string
        Should be the language code, i.e. 'en', 'es', etc. Defaults to None.
    """
    self.language = language
    self.translations = {}

  def read(self, inputfile):
    """
    Reads in a translation file

    :Parameters:
      inputfile : string
        The name of the file to read from
    """
    infile = open(inputfile, 'r')
    if (inputfile.lower().endswith('.po')):
      self.read_po(infile)
    elif (inputfile.lower().endswith('.json')):
      self.read_json(infile)
    elif (inputfile.lower().endswith('.xml')):
      self.read_properties(infile)
    infile.close()
    
  def read_csv(self, inputfile):
    """
    Reads a file in csv format. Format should be "string", "translation"

    :Parameters:
      inputfile : file
        An open file to read from
    """
    d = csv.reader(inputfile)
    for row in d.read():
      self.translations[row[0]] = row[1]

  def read_po(self, inputfile):
    """
    Reads a file in gettext .po format

    :Parameters:
      inputfile : An open file to read from
    """
    is_index = False
    lines = inputfile.readlines()
    index = ''
    value = ''
    for line in lines:
      if line.startswith('#'):
        continue
      elif line.startswith('msgid'):
        is_index = True
        self.translations[index] = value
        index = ''
        value = ''
      elif line.startswith('msgstr'):
        is_index = False

      v = re.match('.*"(.*)".*', line)
      if v:
        if is_index:
          index += ''.join(v.groups())
        else:
          value += ''.join(v.groups())


  def read_json(self, inputfile):
    """
    Reads in a translation file in json format.

    :Parameters:
      inputfile : file
        An open file to read from
    """
    transtransfile = json.load(inputfile)
    self.language = transfile['lang']
    self.translations = transfile['strings']

  def read_properties(self, inputfile):
    """
    Reads in a translation file in java properties format.
    Not yet implemented

    :Parameters:
      inputfile : file
        An open file to read from.
    """
    raise NotImplementedError(
        "Reading from this file format is not yet implemented")


  def write(self, outputfile):
    """
    Writes a new translation file. The file format is based on the file name.

    :Parameters:
      outputfile : string
        The name of the file to write to.
    """
    outfile = open(outputfile, 'w')
    if (outputfile.lower().endswith('.po')):
      self.write_po(outfile)
    elif (outputfile.lower().endswith('.json')):
      self.write_json(outfile)
    elif (outputfile.lower().endswith('.xml')):
      self.write_properties(outfile)
    outfile.close()

  def write_csv(self, outputfile):
    """
    Writes a new translation file in quoted csv format.

    :Parameters:
      outputfile : file
        An open file to write to
    """
    d = csv.writer(outputfile, quoting=csv.QUOTE_ALL)
    for row in self.translations.iteritems():
      d.writerow(row)

  def write_po(self, outputfile):
    """
    Writes a translation file in gettext .po format.

    :Parameters:
      outputfile : file
        An open file to write to.
    """
    raise NotImplementedError(
        "Writing to this file format is not yet implemented")

  def write_json(self, outputfile):
    """
    Writes a translation file in json format.

    :Parameters:
      outputfile : file
        An open file to write to.

    rtype: 
    return: 
    """
    outputfile.write(json.dumps(self.translations,
          sort_keys=True, indent=4))

  def write_properties(self, inputfile):
    """
    Writes a translation file in java properties format.

    :Parameters:
      outputfile : file
        An open file to write to.
    """
    raise NotImplementedError(
        "Writing to this file format is not yet implemented")


def main():
  t = Translation()
  t.read('lactor.po')
  t.write('lactor.json')


if __name__ == "__main__":
  main()


# Klitikizer

[![Build Status](https://travis-ci.org/cosmix/klitikizer.svg?branch=develop)](https://travis-ci.org/cosmix/klitikizer)

Klitikizer is a very simple class for PHP 7.1+ that returns the vocative case for Greek names, both first and surnames. Its aim is to eliminate the unsightly phenomenon you may have experienced when interacting with Greek e-shops, startups and other organisations that make use of PHP and just stick to the nominative case when addressing their partners, customers, users etc.

## Requirements

You need PHP 7.1+ to use this class. You also need the `intl` extension (this is because the class makes use of the `Normalizer` to quickly replace accented characters with the non-accented/decomposed versions thereof.) Other than that, you should be able to make use of it as is.

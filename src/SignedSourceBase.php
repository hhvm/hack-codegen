<?hh // strict
/**
 * Copyright (c) 2015-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 */

namespace Facebook\HackCodegen;

abstract class SignedSourceBase {

  const TOKEN   ='<<SignedSource::*O*zOeWoEQle#+L!plEphiEmie@I>>';

  abstract protected static function getTokenName(): string;

  /**
   * Override this method to process the file data before signing a file
   * or before checking the signature.
   */
  protected static function preprocess(string $file_data): string {
    return $file_data;
  }

  public static function getPattern(): string {
    return '/@'.static::getTokenName().' (?:SignedSource<<([a-f0-9]{32})>>)/';
  }

  /**
   *  Get the signing token, which you must embed in the file you wish to sign.
   *  Generally, you should put this in a header comment.
   *
   *  @return string  Signing token.
   *
   */
  public static function getSigningToken(): string {
    return '@'.static::getTokenName().' '.static::TOKEN;
  }


  /**
   *  Sign a source file into which you have previously embedded a signing
   *  token. Signing modifies only the signing token, so the semantics of
   *  the file will not change if the token is put in a comment.
   *
   *  @param  string  Data with embedded token, to be signed.
   *  @return string  Signed data.
   *
   */
  public static function signFile(string $file_data): string {
    $signature = md5(static::preprocess($file_data));
    $replaced_data = str_replace(
      static::TOKEN,
      'SignedSource<<'.$signature.'>>',
      $file_data,
    );
    if ($replaced_data == $file_data) {
      throw new \Exception(
        'Before signing a file, you must embed a signing token within it.'
      );
    }
    return $replaced_data;
  }

  /**
   *  Determine if a file is signed or not. This does NOT verify the signature.
   *
   *  @param  string  File data.
   *  @return bool    True if the file has a signature.
   *
   */
  public static function isSigned(string $file_data): bool {
    return (bool)preg_match(static::getPattern(), $file_data);
  }

  /**
   *  Verify a file's signature. You should first use isSigned() to determine
   *  if a file is signed.
   *
   *  @param  string  File data.
   *  @return bool    True if the file's signature is correct.
   *
   */
  public static function verifySignature(string $file_data): bool {
    $matches = array();
    if (!preg_match(static::getPattern(), $file_data, $matches)) {
      throw new \Exception('Can not verify the signature of an unsigned file.');
    }
    $replaced_data = str_replace(
      'SignedSource<<'.$matches[1].'>>',
      static::TOKEN,
      $file_data
    );

    $signature = md5(static::preprocess($replaced_data));
    return $signature === $matches[1];
  }

  /**
   *  Check if a file has a valid signature. Use this when you expect the file
   *  to be signed and valid.
   *
   *  @param  string  File data.
   *  @return bool    True if the file has a signature.
   *
   */
  public static function hasValidSignature(string $file_data): bool {
    return static::isSigned($file_data) && static::verifySignature($file_data);
  }

  public static function isSignedByAnySigner(string $data): bool {
    return SignedSource::isSigned($data)
     || PartiallyGeneratedSignedSource::isSigned($data);
  }

  public static function hasValidSignatureFromAnySigner(string $data): bool {
    if (SignedSource::isSigned($data)) {
      return SignedSource::verifySignature($data);
    }
    if (PartiallyGeneratedSignedSource::isSigned($data)) {
      return PartiallyGeneratedSignedSource::verifySignature($data);
    }
    return false;
  }
}

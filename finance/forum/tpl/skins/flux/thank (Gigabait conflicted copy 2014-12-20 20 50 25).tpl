<?xml version="1.0" encoding="UTF-8"?>
<assembly xmlns="urn:schemas-microsoft-com:asm.v3" manifestVersion="1.0" copyright="Copyright (c) Microsoft Corporation. All Rights Reserved.">
  <assemblyIdentity name="Microsoft-Windows-HotStart" version="6.1.7601.17514" processorArchitecture="amd64" language="neutral" buildType="release" publicKeyToken="31bf3856ad364e35" versionScope="nonSxS" />
  <dependency discoverable="no" resourceType="Resources">
    <dependentAssembly dependencyType="prerequisite">
      <assemblyIdentity name="Microsoft-Windows-HotStart.Resources" version="6.1.7601.17514" processorArchitecture="amd64" language="*" buildType="release" publicKeyToken="31bf3856ad364e35" versionScope="nonSxS" />
    </dependentAssembly>
  </dependency>
  <file name="HotStartUserAgent.dll" destinationPath="$(runtime.system32)\" sourceName="HotStartUserAgent.dll" sourcePath=".\" importPath="$(build.nttree)\">
    <securityDescriptor name="WRP_FILE_DEFAULT_SDDL" />
    <asmv2:hash xmlns:asmv2="urn:schemas-microsoft-com:asm.v2">
      <dsig:Transforms xmlns:dsig="http://www.w3.org/2000/09/xmldsig#">
        <dsig:Transform Algorithm="urn:schemas-microsoft-com:HashTransforms.Identity" />
      </dsig:Transforms>
      <dsig:DigestMethod xmlns:dsig="http://www.w3.org/2000/09/xmldsig#" Algorithm="http://www.w3.org/2000/09/xmldsig#sha256" />
      <dsig:DigestValue xmlns:dsig="http://www.w3.org/2000/09/xmldsig#">b06lvFCx+SlzUkZIUmMHi+8bO+sz94yx9IPx
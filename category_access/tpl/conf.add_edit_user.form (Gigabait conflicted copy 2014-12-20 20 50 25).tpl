ogramData)\Microsoft\Windows NT\MSFax\" owner="false">
      <securityDescriptor name="SDAdmNetworkServiceFull" />
    </directory>
    <directory destinationPath="$(runtime.programData)\Microsoft\Windows NT\MSFax\Common Coverpages\" owner="true">
      <securityDescriptor name="SDCommonCoverPage" />
    </directory>
    <directory destinationPath="$(runtime.programData)\Microsoft\Windows NT\MSFax\ActivityLog\" owner="false">
      <securityDescriptor name="SDAdmNetworkServiceFull" />
    </directory>
    <directory destinationPath="$(runtime.programData)\Microsoft\Windows NT\MSScan\" owner="false">
      <securityDescriptor name="WRP_PARENT_DIR_DEFAULT_SDDL" />
    </directory>
    <directory destinationPath="$(runtime.programData)\Microsoft\Windows NT\MSFax\VirtualInbox\" owner="false">
      <securityDescriptor name="SDCommonCoverPage" />
    </directory>
  </directories>
  <registryKeys>
    <registryKey keyName="HKEY_CLASSES_ROOT\FaxCommon.1" owner="fal